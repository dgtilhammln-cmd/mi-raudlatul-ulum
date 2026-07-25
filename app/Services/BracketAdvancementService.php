<?php

namespace App\Services;

use App\Models\{Event, Round, Participant, UserNotification};
use Illuminate\Support\Collection;

class BracketAdvancementService
{
    /**
     * Cek & trigger auto-advancement jika memenuhi syarat.
     * Dipanggil dari Observer setiap ada session submit atau esai dinilai.
     */
    public function tryAutoAdvance(Round $round): ?array
    {
        // Hanya untuk event kualifikasi
        if (!$round->event->isQualificationSystem()) {
            return null;
        }

        // Hanya jika auto_advance aktif
        if (!$round->auto_advance) {
            return null;
        }

        // Cek apakah babak siap diproses
        if (!$round->isReadyToAdvance()) {
            return null;
        }

        return $this->executeAdvancement($round);
    }

    /**
     * Eksekusi advancement — ambil Top-N, update participant status, sync ke babak berikutnya.
     */
    public function executeAdvancement(Round $round): array
    {
        // Tandai sedang diproses (mencegah double-processing)
        $round->update(['advancement_status' => 'processing']);

        $limit = $round->advancement_limit ?? 0;
        $nextRound = $round->nextRound();
        $isFinalRound = $round->round_type === 'final';

        // Dapatkan semua peserta yang mengikuti babak ini
        $allParticipantIds = $round->participants()->pluck('participants.id');

        // Dapatkan Top-N peserta
        $advanced = $limit > 0
            ? $round->getTopNParticipants($limit)
            : collect();

        $advancedIds = $advanced->pluck('id')->toArray();

        // Peserta yang TIDAK lolos = eliminated
        $eliminatedIds = $allParticipantIds->diff($advancedIds)->values()->toArray();

        // Update peserta yang gugur
        if (!empty($eliminatedIds)) {
            Participant::whereIn('id', $eliminatedIds)->update([
                'eliminated_at_round' => $round->sequence,
                'status'              => 'completed',
            ]);
        }

        // Jika ini babak final
        if ($isFinalRound && $advanced->isNotEmpty()) {
            // Top-1 adalah juara
            $champion = $advanced->first();
            Participant::where('id', $champion->id)->update([
                'is_champion'            => true,
                'current_round_sequence' => $round->sequence,
                'status'                 => 'completed',
            ]);

            // Selain juara di babak final = runner-up (tidak gugur tapi tidak juara)
            if ($advanced->count() > 1) {
                $runnerUpIds = $advanced->slice(1)->pluck('id')->toArray();
                Participant::whereIn('id', $runnerUpIds)->update([
                    'current_round_sequence' => $round->sequence,
                    'status'                 => 'completed',
                ]);
            }

            // Notif juara
            $this->notifyChampion($champion, $round->event);

        } elseif ($nextRound && !$isFinalRound) {
            // Update current_round_sequence peserta yang lolos
            if ($advancedIds) {
                Participant::whereIn('id', $advancedIds)->update([
                    'current_round_sequence' => $nextRound->sequence,
                ]);
            }

            // Sync peserta lolos ke participant_round babak berikutnya
            $nextRound->participants()->syncWithoutDetaching($advancedIds);
        }

        // Tandai babak selesai diproses
        $round->update(['advancement_status' => 'done']);

        // Kirim notifikasi
        $this->notifyAdvancement($round, $advanced, Participant::whereIn('id', $eliminatedIds)->get(), $nextRound);

        return [
            'advanced'    => $advanced->count(),
            'eliminated'  => count($eliminatedIds),
            'next_round'  => $nextRound?->name,
        ];
    }

    /**
     * Preview: siapa yang akan lolos jika advancement dijalankan sekarang.
     */
    public function previewAdvancement(Round $round): array
    {
        $limit      = $round->advancement_limit ?? 0;
        $topN       = $limit > 0 ? $round->getTopNParticipants($limit) : collect();
        $allIds     = $round->participants()->pluck('participants.id');
        $eliminatedIds = $allIds->diff($topN->pluck('id'));

        return [
            'will_advance'    => $topN->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->user->name ?? '—',
                'institution' => $p->institution ?? '—',
                'score'       => $p->examSessions->where('round_id', $round->id)->first()?->total_score ?? 0,
            ])->values()->toArray(),
            'will_eliminate'  => Participant::whereIn('id', $eliminatedIds)->with('user')->get()->map(fn($p) => [
                'id'          => $p->id,
                'name'        => $p->user->name ?? '—',
                'institution' => $p->institution ?? '—',
            ])->values()->toArray(),
            'advancement_limit' => $limit,
            'ready'             => $round->isReadyToAdvance(),
        ];
    }

    // ─── Notifications ───────────────────────────────────────────────────────

    private function notifyAdvancement(Round $round, Collection $advanced, Collection $eliminated, ?Round $nextRound): void
    {
        $nextLabel = $nextRound
            ? "{$nextRound->name} (mulai {$nextRound->start_time?->translatedFormat('d M Y, H:i')})"
            : 'babak selanjutnya';

        // Lolos
        foreach ($advanced as $p) {
            if (!$p->user_id) continue;
            UserNotification::send(
                $p->user_id,
                'bracket_advanced',
                '🎉',
                'Anda Lolos ke Babak Berikutnya!',
                "Selamat! Anda lolos dari {$round->name} dan berhak mengikuti {$nextLabel}.",
            );
        }

        // Gugur
        foreach ($eliminated as $p) {
            if (!$p->user_id) continue;
            UserNotification::send(
                $p->user_id,
                'bracket_eliminated',
                '📋',
                'Hasil Babak Telah Keluar',
                "Anda belum berhasil lolos dari {$round->name}. Jangan menyerah, terus semangat!",
            );
        }
    }

    private function notifyChampion(Participant $champion, Event $event): void
    {
        if (!$champion->user_id) return;
        UserNotification::send(
            $champion->user_id,
            'bracket_champion',
            '🏆',
            'SELAMAT! Anda Menjadi JUARA!',
            "Luar biasa! Anda berhasil menjadi Juara {$event->name}. Prestasi yang sangat membanggakan!",
        );
    }

    /**
     * Kirim notifikasi perubahan jadwal babak ke semua peserta babak tsb.
     */
    public function notifyScheduleChange(Round $round, string $oldTime, string $newTime): void
    {
        $userIds = $round->participants()->pluck('participants.user_id');

        foreach ($userIds as $userId) {
            if (!$userId) continue;
            UserNotification::send(
                $userId,
                'schedule_changed',
                'ℹ️',
                'Jadwal Babak Berubah',
                "Jadwal {$round->name} diperbarui. Waktu mulai baru: {$newTime}. Harap persiapkan diri.",
            );
        }
    }
}
