<?php

namespace App\Services;

use App\Models\{User, Event, Participant, ImportLog};
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

class ImportService
{
    /**
     * Import peserta dari data array (hasil parsing Excel).
     *
     * @param Event $event
     * @param array $rows  [['participant_id' => '...', 'name' => '...', 'access_code' => '...', 'institution' => '...', 'grade' => '...'], ...]
     * @param int $organizerId
     * @param string $filename
     * @return ImportLog
     */
    public function importParticipants(Event $event, array $rows, int $organizerId, string $filename): ImportLog
    {
        $log = ImportLog::create([
            'event_id' => $event->id,
            'organizer_id' => $organizerId,
            'filename' => $filename,
            'total_rows' => count($rows),
            'status' => 'processing',
        ]);

        $successCount = 0;
        $failedCount = 0;
        $errors = [];
        $accessCodes = []; // Simpan plaintext untuk export

        DB::beginTransaction();
        try {
            foreach ($rows as $index => $row) {
                $rowNum = $index + 2; // +2 karena baris 1 = header

                // Validasi
                $participantId = trim($row['participant_id'] ?? '');
                $name = trim($row['name'] ?? '');

                if (empty($participantId)) {
                    $errors[] = ['row' => $rowNum, 'message' => 'ID Peserta kosong'];
                    $failedCount++;
                    continue;
                }

                if (empty($name)) {
                    $errors[] = ['row' => $rowNum, 'message' => 'Nama kosong'];
                    $failedCount++;
                    continue;
                }

                // Cek duplikat participant_id di event ini
                $existingUser = User::where('participant_id', $participantId)->first();

                if ($existingUser) {
                    $existingParticipant = Participant::where('event_id', $event->id)
                        ->where('user_id', $existingUser->id)
                        ->first();

                    if ($existingParticipant) {
                        $errors[] = ['row' => $rowNum, 'message' => "ID Peserta '{$participantId}' sudah terdaftar di event ini"];
                        $failedCount++;
                        continue;
                    }
                }

                // Generate atau pakai access code dari Excel
                $accessCode = trim($row['access_code'] ?? '');
                if (empty($accessCode)) {
                    $accessCode = Str::random(6); // Generate random 6 karakter
                }

                // Buat atau update user
                $user = $existingUser ?? User::create([
                    'name' => $name,
                    'participant_id' => $participantId,
                    'password' => Hash::make($accessCode),
                    'role' => 'participant',
                    'is_active' => true,
                ]);

                if ($existingUser) {
                    // Update password jika user sudah ada tapi belum di event ini
                    $user->update([
                        'name' => $name,
                        'password' => Hash::make($accessCode),
                    ]);
                }

                // Buat participant entry
                Participant::create([
                    'event_id'         => $event->id,
                    'user_id'          => $user->id,
                    'participant_code' => $participantId,
                    'access_code'      => $accessCode,
                    'institution'      => trim($row['institution'] ?? ''),
                    'grade'            => trim($row['grade'] ?? ''),
                    'major'            => trim($row['major'] ?? $row['jurusan'] ?? ''),
                    'status'           => 'registered',
                ]);

                // Simpan plaintext untuk export
                $accessCodes[] = [
                    'participant_id' => $participantId,
                    'name' => $name,
                    'access_code' => $accessCode,
                    'institution' => trim($row['institution'] ?? ''),
                ];

                $successCount++;
            }

            DB::commit();

            // Auto-sync participants to the first round (Sequence 1)
            $firstRound = $event->rounds()->orderBy('sequence')->first();
            if ($firstRound) {
                $allParticipantIds = Participant::where('event_id', $event->id)->pluck('id')->toArray();
                $firstRound->participants()->syncWithoutDetaching($allParticipantIds);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $log->update([
                'status' => 'failed',
                'errors' => [['row' => 0, 'message' => 'Terjadi error: ' . $e->getMessage()]],
            ]);
            return $log;
        }

        $log->update([
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'errors' => $errors ?: null,
            'access_codes' => $accessCodes,
            'status' => 'done',
        ]);

        return $log;
    }
}
