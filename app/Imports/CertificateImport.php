<?php

namespace App\Imports;

use App\Models\Participant;
use App\Models\UserNotification;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CertificateImport implements ToModel, WithHeadingRow
{
    private $eventId;
    public $successCount = 0;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;
    }

    public function model(array $row)
    {
        if (!isset($row['kode_peserta']) || !isset($row['link_drive'])) {
            return null;
        }

        $participant = Participant::with('user')
            ->where('event_id', $this->eventId)
            ->where('participant_code', $row['kode_peserta'])
            ->first();

        if ($participant) {
            $participant->update([
                'certificate_link' => $row['link_drive']
            ]);
            $this->successCount++;

            // Kirim notifikasi ke user peserta
            if ($participant->user_id) {
                UserNotification::send(
                    userId:      $participant->user_id,
                    type:        'success',
                    icon:        'fas fa-certificate',
                    title:       '🎉 Sertifikat Anda Sudah Siap!',
                    body:        'E-Sertifikat keikutsertaan Anda telah diterbitkan. Silakan buka menu Event & E-Sertifikat untuk melihat dan mengunduh sertifikat Anda.',
                    actionUrl:   '/peserta/events',
                    actionLabel: 'Lihat Event & E-Sertifikat'
                );
            }
        }

        return null; // Return null because we updated manually instead of creating a new row
    }
}
