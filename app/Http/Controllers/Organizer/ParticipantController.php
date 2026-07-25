<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\{Event, Participant, ImportLog};
use App\Services\ImportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ParticipantController extends Controller
{
    public function index(Event $event)
    {
        $participants = $event->participants()->with('user')->latest()->paginate(20);
        $importLogs = ImportLog::where('event_id', $event->id)->latest()->take(5)->get();

        return view('organizer.participants.index', compact('event', 'participants', 'importLogs'));
    }

    public function template()
    {
        $filename = "template_peserta_olimpiade.csv";
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel UTF-8
            fputcsv($file, ['participant_id', 'name', 'access_code', 'institution', 'grade', 'major']);
            fputcsv($file, ['MHS001', 'Budi Santoso', '', 'Universitas Indonesia', 'S1', 'Ilmu Komputer']);
            fputcsv($file, ['MHS002', 'Siti Aminah', 'SITI123', 'Institut Teknologi Bandung', 'S1', 'Sistem Informasi']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request, Event $event)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120', // Max 5MB
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        // Parse Excel file
        $rows = [];
        try {
            $data = Excel::toArray(null, $file);
            $sheet = $data[0] ?? [];

            if (count($sheet) < 2) {
                return back()->withErrors(['file' => 'File Excel kosong atau hanya berisi header.']);
            }

            $header = array_map('strtolower', array_map('trim', $sheet[0]));

            // Map header ke field
            $headerMap = [
                'participant_id' => $this->findHeaderIndex($header, ['participant_id', 'id_peserta', 'id peserta', 'no_peserta', 'kode_peserta']),
                'name' => $this->findHeaderIndex($header, ['name', 'nama', 'nama_peserta', 'nama peserta']),
                'access_code' => $this->findHeaderIndex($header, ['access_code', 'kode_akses', 'kode akses', 'password']),
                'institution' => $this->findHeaderIndex($header, ['institution', 'institusi', 'asal', 'sekolah', 'universitas']),
                'grade' => $this->findHeaderIndex($header, ['grade', 'kelas', 'tingkat', 'angkatan']),
            ];

            if ($headerMap['participant_id'] === null || $headerMap['name'] === null) {
                return back()->withErrors(['file' => 'File harus memiliki kolom "participant_id/id_peserta" dan "name/nama".']);
            }

            // Parse rows
            for ($i = 1; $i < count($sheet); $i++) {
                $row = $sheet[$i];
                if (empty(array_filter($row))) continue; // Skip empty rows

                $rows[] = [
                    'participant_id' => $row[$headerMap['participant_id']] ?? '',
                    'name' => $row[$headerMap['name']] ?? '',
                    'access_code' => $headerMap['access_code'] !== null ? ($row[$headerMap['access_code']] ?? '') : '',
                    'institution' => $headerMap['institution'] !== null ? ($row[$headerMap['institution']] ?? '') : '',
                    'grade' => $headerMap['grade'] !== null ? ($row[$headerMap['grade']] ?? '') : '',
                ];
            }
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal membaca file: ' . $e->getMessage()]);
        }

        // Process import
        $importService = new ImportService();
        $log = $importService->importParticipants($event, $rows, $request->user()->id, $filename);

        return redirect()->route('organizer.participants.import.result', [$event, $log])
            ->with('success', "Import selesai: {$log->success_count} berhasil, {$log->failed_count} gagal.");
    }

    public function importResult(Event $event, ImportLog $importLog)
    {
        return view('organizer.participants.import-result', compact('event', 'importLog'));
    }

    public function exportAccessList(Event $event, ImportLog $importLog)
    {
        if (!$importLog->access_codes) {
            return back()->withErrors(['error' => 'Data kode akses tidak tersedia.']);
        }

        // Generate CSV download
        $filename = "daftar-akses-{$event->name}-" . date('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($importLog) {
            $file = fopen('php://output', 'w');
            // BOM for Excel UTF-8
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, ['No', 'ID Peserta', 'Nama', 'Kode Akses', 'Institusi']);

            foreach ($importLog->access_codes as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row['participant_id'],
                    $row['name'],
                    $row['access_code'],
                    $row['institution'] ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(Participant $participant)
    {
        $event = $participant->event;
        $participant->user()->delete(); // Soft delete user juga
        $participant->delete();

        return redirect()->route('organizer.participants.index', $event)
            ->with('success', 'Peserta berhasil dihapus.');
    }

    private function findHeaderIndex(array $header, array $possibleNames): ?int
    {
        foreach ($possibleNames as $name) {
            $index = array_search($name, $header);
            if ($index !== false) return $index;
        }
        return null;
    }

    public function destroyAll(Event $event)
    {
        // Temukan semua participant milik event ini
        $participants = Participant::where('event_id', $event->id)->get();
        foreach ($participants as $p) {
            if ($p->user) $p->user->delete();
            $p->delete();
        }

        return back()->with('success', 'Semua peserta pada event ini berhasil dihapus.');
    }
}
