<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Models\{ExamSession, Certificate};
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function show(ExamSession $session)
    {
        if ($session->participant->user_id !== auth()->id()) {
            abort(403);
        }

        $session->load([
            'round.event',
            'participant.user',
            'answers.question.options',
            'answers.selectedOption',
            'certificate',
        ]);

        return view('peserta.result.show', compact('session'));
    }

    public function downloadCertificate(Certificate $certificate)
    {
        if ($certificate->participant->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$certificate->file_path || !file_exists(storage_path('app/' . $certificate->file_path))) {
            return back()->withErrors(['error' => 'Sertifikat belum tersedia.']);
        }

        $certificate->increment('downloaded_count');

        return response()->download(
            storage_path('app/' . $certificate->file_path),
            "Sertifikat-{$certificate->certificate_number}.pdf"
        );
    }
}
