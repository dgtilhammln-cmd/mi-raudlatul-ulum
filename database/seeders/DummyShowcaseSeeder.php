<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Event, Participant, Round, QuestionBank, Question, ExamSession};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DummyShowcaseSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🚀 Starting Dummy Showcase Seeder...');

        // ── Clean slate ────────────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('exam_sessions')->truncate();
        DB::table('round_banks')->truncate();
        DB::table('participant_round')->truncate();
        Round::truncate();
        QuestionBank::truncate();
        Question::truncate();
        Participant::truncate();
        Event::truncate();
        User::where('email', 'like', '%@dummy-showcase.com')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $organizer = User::where('role', 'organizer')->firstOrFail();

        // ── Create 300 dummy users (bulk insert) ───────────────────────────
        $this->command->info('Creating 300 Dummy Users...');
        $password = bcrypt('password');
        $now      = now();
        $users    = [];
        for ($i = 1; $i <= 300; $i++) {
            $users[] = [
                'name'       => "Peserta Dummy $i",
                'email'      => "peserta{$i}_" . \Illuminate\Support\Str::random(6) . "@dummy-showcase.com",
                'participant_id' => "SIM-$i",
                'password'   => $password,
                'role'       => 'participant',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        foreach (array_chunk($users, 100) as $chunk) {
            User::insert($chunk);
        }
        $userIds = User::where('email', 'like', '%@dummy-showcase.com')
                       ->pluck('id')->toArray();
        $this->command->info('300 users created ✓');

        // ── Build the 3 events ─────────────────────────────────────────────
        $this->seedPointEvent($organizer, $userIds);
        $this->seedQualificationEvent($organizer, $userIds, 3);
        $this->seedQualificationEvent($organizer, $userIds, 6);

        $this->command->info('✅ Done! 3 fully-completed events seeded.');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  EVENT 1 — Sistem Poin (point)
    // ══════════════════════════════════════════════════════════════════════
    private function seedPointEvent($organizer, $userIds)
    {
        $this->command->info('▶ Seeding: Liga Cerdas Cermat Nusantara (Sistem Poin)...');

        $event = Event::create([
            'organizer_id'        => $organizer->id,
            'name'                => 'Liga Cerdas Cermat Nusantara',
            'slug'                => 'liga-cerdas-cermat-nusantara-'.Str::random(4),
            'scoring_system'      => 'point',
            'status'              => 'completed',
            'description'         => 'Event sistem poin dimana semua nilai diakumulasikan dari 3 sesi.',
            'category'            => 'Umum',
            'leaderboard_visible' => true,
            'start_date'          => now()->subDays(10),
            'end_date'            => now()->subDays(1),
        ]);

        $participants = $this->makeParticipants($event->id, $userIds);

        for ($i = 1; $i <= 3; $i++) {
            $bank = $this->makeBank($event->id, "Bank Soal Sesi $i", 25);

            $r = Round::create([
                'event_id'         => $event->id,
                'sequence'         => $i,
                'name'             => "Sesi Pengerjaan $i",
                'round_type'       => 'group_stage',
                'start_time'       => now()->subDays(6 - $i)->setHour(8),
                'end_time'         => now()->subDays(6 - $i)->setHour(10),
                'duration_minutes' => 60,
                'max_questions'    => 25,
                'status'           => 'completed',
            ]);
            $r->questionBanks()->attach($bank->id, ['question_count' => 25]);
            $r->participants()->sync($participants->pluck('id'));

            $this->makeSessionsForAll($participants, $r);
        }

        // Set champion = highest total score across all sessions
        $this->setPointChampion($event, $participants);
        $this->command->info("   ✓ Event 1 done (300 peserta, 3 babak, sistem poin)");
    }

    // ══════════════════════════════════════════════════════════════════════
    //  EVENT 2 & 3 — Sistem Kualifikasi (3 babak / 6 babak)
    // ══════════════════════════════════════════════════════════════════════
    private function seedQualificationEvent($organizer, $userIds, int $roundCount)
    {
        if ($roundCount === 3) {
            $name    = 'Olimpiade Kualifikasi 3 Babak';
            $configs = [
                ['name' => 'Babak Penyisihan', 'type' => 'group_stage', 'limit' => 100],
                ['name' => 'Semifinal',         'type' => 'semi_final',  'limit' => 20 ],
                ['name' => 'Grand Final',        'type' => 'final',       'limit' => 3  ],
            ];
        } else {
            $name    = 'Olimpiade Kualifikasi 6 Babak';
            $configs = [
                ['name' => 'Penyisihan 1',  'type' => 'group_stage', 'limit' => 150],
                ['name' => 'Penyisihan 2',  'type' => 'group_stage', 'limit' => 64 ],
                ['name' => '64 Besar',      'type' => 'round_of_64', 'limit' => 32 ],
                ['name' => '32 Besar',      'type' => 'round_of_32', 'limit' => 16 ],
                ['name' => 'Semifinal',     'type' => 'semi_final',  'limit' => 8  ],
                ['name' => 'Grand Final',   'type' => 'final',       'limit' => 3  ],
            ];
        }

        $this->command->info("▶ Seeding: {$name}...");

        $event = Event::create([
            'organizer_id'        => $organizer->id,
            'name'                => $name,
            'slug'                => Str::slug($name).'-'.Str::random(4),
            'scoring_system'      => 'qualification',
            'status'              => 'completed',
            'description'         => "Event kualifikasi dengan {$roundCount} babak. Peserta terbaik lolos ke babak berikutnya.",
            'category'            => 'Kompetisi',
            'bracket_mode'        => 'full',
            'leaderboard_visible' => true,
            'start_date'          => now()->subDays(15),
            'end_date'            => now()->subDays(1),
        ]);

        $participants = $this->makeParticipants($event->id, $userIds);
        $pool         = collect($participants);
        $totalRounds  = count($configs);

        foreach ($configs as $idx => $cfg) {
            $isFinal = ($idx === $totalRounds - 1);
            $bank    = $this->makeBank($event->id, "Bank Soal - {$cfg['name']}", 30);

            $r = Round::create([
                'event_id'          => $event->id,
                'sequence'          => $idx + 1,
                'name'              => $cfg['name'],
                'round_type'        => $cfg['type'],
                'start_time'        => now()->subDays(15 - $idx)->setHour(8),
                'end_time'          => now()->subDays(15 - $idx)->setHour(10),
                'duration_minutes'  => 60,
                'max_questions'     => 30,
                'advancement_limit' => $cfg['limit'],
                'auto_advance'      => true,
                'status'            => 'completed',
                'advancement_status'=> 'done',
            ]);
            $r->questionBanks()->attach($bank->id, ['question_count' => 30]);
            $r->participants()->sync($pool->pluck('id'));

            // Score everyone in current pool
            $scored = $pool->map(function ($p) use ($r) {
                $score = rand(40, 100) + (rand(0, 9) / 10);
                ExamSession::create([
                    'participant_id' => $p->id,
                    'round_id'       => $r->id,
                    'token'          => \Illuminate\Support\Str::random(32),
                    'started_at'     => $r->start_time,
                    'submitted_at'   => (clone $r->start_time)->addMinutes(50),
                    'status'         => 'submitted',
                    'result_status'  => 'final',
                    'total_score'    => $score,
                    'score_pg'       => $score,
                ]);
                return ['participant' => $p, 'score' => $score];
            })->sortByDesc('score')->values();

            if ($isFinal) {
                // Mark champion
                foreach ($scored as $i => $row) {
                    $p = $row['participant'];
                    if ($i === 0) {
                        $p->is_champion = true;
                    } else {
                        $p->eliminated_at_round = $r->sequence;
                    }
                    $p->save();
                }
            } else {
                // Advance top N, eliminate rest
                $advanced    = $scored->take($cfg['limit'])->pluck('participant');
                $eliminated  = $scored->skip($cfg['limit'])->pluck('participant');

                foreach ($eliminated as $p) {
                    $p->eliminated_at_round = $r->sequence;
                    $p->save();
                }

                $pool = $advanced;
            }

            $this->command->info("   Babak {$cfg['name']}: {$pool->count()} lolos ke babak berikutnya");
        }

        $this->command->info("   ✓ {$name} done ({$totalRounds} babak selesai, juara ditentukan)");
    }

    // ══════════════════════════════════════════════════════════════════════
    //  HELPERS
    // ══════════════════════════════════════════════════════════════════════

    private function makeParticipants($eventId, $userIds)
    {
        $institutions = ['SMA 1 Surabaya', 'MAN 1 Gresik', 'SMKN 2 Surabaya', 'SMA 5 Bandung', 'SMAN 3 Malang'];
        $now = now();
        $rows = [];
        $seq  = 1;
        foreach ($userIds as $uid) {
            $rows[] = [
                'event_id'         => $eventId,
                'user_id'          => $uid,
                'participant_code' => 'SIM-'.str_pad($seq++, 3, '0', STR_PAD_LEFT),
                'institution'      => $institutions[array_rand($institutions)],
                'grade'            => ['X', 'XI', 'XII'][array_rand(['X', 'XI', 'XII'])],
                'major'            => ['IPA', 'IPS'][array_rand(['IPA', 'IPS'])],
                'certificate_link' => 'https://drive.google.com/file/d/12KfvzoyiQGRQkQqsRfNIVJOATMXuIJ6_/view?usp=sharing',
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }
        foreach (array_chunk($rows, 100) as $chunk) {
            Participant::insert($chunk);
        }
        return Participant::where('event_id', $eventId)->get();
    }

    private function makeBank($eventId, $name, $questionCount)
    {
        $bank = QuestionBank::create([
            'event_id'    => $eventId,
            'name'        => $name,
            'description' => "Soal khusus: $name",
        ]);
        $qs = [];
        $now = now();
        for ($i = 1; $i <= $questionCount; $i++) {
            $qs[] = [
                'bank_id'    => $bank->id,
                'type'       => 'multiple_choice',
                'content'    => "Soal dummy nomor $i dari bank soal: $name.",
                'score'      => 2,
                'difficulty' => ['easy', 'medium', 'hard'][array_rand(['easy', 'medium', 'hard'])],
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Question::insert($qs);
        return $bank;
    }

    private function makeSessionsForAll($participants, Round $r)
    {
        foreach ($participants as $p) {
            ExamSession::create([
                'participant_id' => $p->id,
                'round_id'       => $r->id,
                'token'          => \Illuminate\Support\Str::random(32),
                'started_at'     => $r->start_time,
                'submitted_at'   => (clone $r->start_time)->addMinutes(50),
                'status'         => 'submitted',
                'result_status'  => 'final',
                'total_score'    => rand(40, 100),
                'score_pg'       => rand(40, 100),
            ]);
        }
    }

    private function setPointChampion($event, $participants)
    {
        // For point system — top scorer by total across all sessions = champion
        $topParticipant = $participants->map(function ($p) {
            return [
                'participant' => $p,
                'total'       => ExamSession::where('participant_id', $p->id)
                                           ->where('status', 'submitted')
                                           ->sum('total_score'),
            ];
        })->sortByDesc('total')->first();

        if ($topParticipant) {
            $topParticipant['participant']->is_champion = true;
            $topParticipant['participant']->save();
        }
    }
}
