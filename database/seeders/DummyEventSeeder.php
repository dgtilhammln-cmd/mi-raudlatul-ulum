<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Round;
use App\Models\User;
use App\Models\Participant;
use App\Models\ExamSession;
use App\Models\QuestionBank;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DummyEventSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada organizer
        $organizer = User::firstOrCreate(
            ['email' => 'admin@musabaqahtarikhislam.com'],
            [
                'name' => 'Admin HM SPI UINSA',
                'password' => Hash::make('password'),
                'role' => 'organizer',
                'is_active' => true,
            ]
        );

        $this->createEventPointSystem($organizer);
        $this->createEventQualificationSystem($organizer);
    }

    private function createEventPointSystem($organizer)
    {
        // 1. Buat Event (Akumulasi Poin)
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'name' => 'Liga Sejarah Nusantara 2026 (Sistem Poin)',
            'description' => 'Kompetisi dengan sistem akumulasi poin. Klasemen terlihat secara realtime.',
            'category' => 'Liga Nasional',
            'start_date' => Carbon::now()->subDays(2),
            'end_date' => Carbon::now()->addDays(5),
            'status' => 'ongoing',
            'scoring_system' => 'point',
            'leaderboard_visible' => true,
            'settings' => [
                'show_score_immediately' => true,
                'show_answer_review' => false,
                'essay_review_hours' => 24,
                'certificate_auto_publish' => true,
            ],
        ]);

        // 2. Buat Bank Soal
        $bank = QuestionBank::create([
            'event_id' => $event->id,
            'name' => 'Bank Soal Liga Poin',
            'description' => 'Soal gabungan untuk liga sejarah.',
        ]);

        // 3. Buat Soal
        for ($i = 1; $i <= 10; $i++) {
            $q = Question::create([
                'bank_id' => $bank->id,
                'type' => 'multiple_choice',
                'content' => "Soal pilihan ganda contoh ke-$i untuk sistem poin. Manakah jawaban yang benar?",
                'explanation' => 'Penjelasan untuk soal ini.',
                'score' => 10,
            ]);
            // Options
            for ($o = 1; $o <= 4; $o++) {
                Option::create([
                    'question_id' => $q->id,
                    'content' => "Pilihan $o",
                    'is_correct' => ($o === 1), // selalu pilihan 1 yg benar di contoh ini
                ]);
            }
        }

        // 4. Buat Rounds
        $round1 = Round::create([
            'event_id' => $event->id,
            'name' => 'Match 1 - Klasik',
            'sequence' => 1,
            'start_time' => Carbon::now()->subDays(1),
            'end_time' => Carbon::now()->subDays(1)->addHours(2),
            'duration_minutes' => 60,
            'max_questions' => 5,
            'status' => 'completed',
        ]);
        $round1->questionBanks()->attach($bank->id);

        $round2 = Round::create([
            'event_id' => $event->id,
            'name' => 'Match 2 - Pertengahan',
            'sequence' => 2,
            'start_time' => Carbon::now()->subHours(2),
            'end_time' => Carbon::now()->addHours(2),
            'duration_minutes' => 60,
            'max_questions' => 5,
            'status' => 'ongoing',
        ]);
        $round2->questionBanks()->attach($bank->id);

        // 5. Buat Peserta & Sesi
        $institutions = ['SMA 1 Surabaya', 'MAN 2 Malang', 'SMK 3 Bandung', 'SMA Taruna Nusantara', 'MAN Insan Cendekia'];
        $names = ['Ahmad Budi Santoso', 'Siti Aminah', 'Rizky Pratama', 'Dewi Lestari', 'Bima Satria', 'Aisyah Putri', 'Rahmat Hidayat', 'Nadia Safira', 'Fajar Ramadhan', 'Indah Permatasari'];
        
        for ($p = 1; $p <= 10; $p++) {
            $accessCode = 'MTI' . str_pad($p, 3, '0', STR_PAD_LEFT);
            $user = User::firstOrCreate(
                ['participant_id' => 'POIN-00' . $p],
                [
                    'name' => $names[$p - 1],
                    'password' => Hash::make($accessCode),
                    'role' => 'participant',
                    'is_active' => true,
                ]
            );

            $participant = Participant::create([
                'event_id'         => $event->id,
                'user_id'          => $user->id,
                'participant_code' => 'POIN-00' . $p,
                'access_code'      => $accessCode,
                'institution'      => $institutions[array_rand($institutions)],
                'grade'            => 'Kelas 11',
                'major'            => 'IPS',
                'status'           => 'active',
            ]);

            $round1->participants()->attach($participant->id);
            $round2->participants()->attach($participant->id);

            // Sesi Round 1 (Semua sudah selesai)
            ExamSession::create([
                'participant_id' => $participant->id,
                'round_id' => $round1->id,
                'token' => Str::random(32),
                'started_at' => $round1->start_time->copy()->addMinutes(rand(1, 10)),
                'submitted_at' => $round1->start_time->copy()->addMinutes(rand(30, 50)),
                'status' => 'submitted',
                'score_pg' => rand(20, 50),
                'total_score' => rand(20, 50),
                'result_status' => 'final',
                'correct_count' => rand(2, 5),
            ]);

            // Sesi Round 2 (Beberapa selesai, beberapa ongoing, beberapa pending)
            if ($p <= 5) {
                // Selesai
                ExamSession::create([
                    'participant_id' => $participant->id,
                    'round_id' => $round2->id,
                    'token' => Str::random(32),
                    'started_at' => $round2->start_time->copy()->addMinutes(rand(1, 10)),
                    'submitted_at' => $round2->start_time->copy()->addMinutes(rand(20, 40)),
                    'status' => 'submitted',
                    'score_pg' => rand(10, 50),
                    'total_score' => rand(10, 50),
                    'result_status' => 'final',
                ]);
            } elseif ($p <= 8) {
                // Ongoing
                ExamSession::create([
                    'participant_id' => $participant->id,
                    'round_id' => $round2->id,
                    'token' => Str::random(32),
                    'started_at' => Carbon::now()->subMinutes(rand(1, 30)),
                    'status' => 'ongoing',
                ]);
            }
        }
    }

    private function createEventQualificationSystem($organizer)
    {
        // 1. Buat Event (Kualifikasi)
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'name' => 'Olimpiade MTI 2026 (Sistem Kualifikasi)',
            'description' => 'Sistem eliminasi babak. Peserta harus mencapai passing score untuk lolos ke babak selanjutnya.',
            'category' => 'Olimpiade',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(10),
            'status' => 'ongoing',
            'scoring_system' => 'qualification',
            'leaderboard_visible' => false,
            'settings' => [
                'show_score_immediately' => true,
                'show_answer_review' => false,
                'essay_review_hours' => 24,
                'certificate_auto_publish' => true,
            ],
        ]);

        // 2. Buat Bank Soal
        $bank = QuestionBank::create([
            'event_id' => $event->id,
            'name' => 'Bank Soal Kualifikasi',
        ]);

        for ($i = 1; $i <= 10; $i++) {
            $q = Question::create([
                'bank_id' => $bank->id,
                'type' => 'multiple_choice',
                'content' => "Soal pilihan ganda contoh ke-$i untuk sistem kualifikasi.",
                'score' => 10,
            ]);
            for ($o = 1; $o <= 4; $o++) {
                Option::create([
                    'question_id' => $q->id,
                    'content' => "Pilihan $o",
                    'is_correct' => ($o === 1),
                ]);
            }
        }

        // 3. Buat Rounds
        $round1 = Round::create([
            'event_id' => $event->id,
            'name' => 'Babak Penyisihan',
            'sequence' => 1,
            'start_time' => Carbon::now()->subDays(4),
            'end_time' => Carbon::now()->subDays(3),
            'duration_minutes' => 60,
            'max_questions' => 10,
            'passing_score' => 60,
            'status' => 'completed',
        ]);
        $round1->questionBanks()->attach($bank->id);

        $round2 = Round::create([
            'event_id' => $event->id,
            'name' => 'Babak Semifinal',
            'sequence' => 2,
            'start_time' => Carbon::now()->subHours(1),
            'end_time' => Carbon::now()->addHours(2),
            'duration_minutes' => 60,
            'max_questions' => 10,
            'passing_score' => 70,
            'status' => 'ongoing',
        ]);
        $round2->questionBanks()->attach($bank->id);

        // 4. Buat Peserta & Sesi
        $institutions = ['SMA 1 Sidoarjo', 'MAN 1 Gresik', 'SMKN 2 Surabaya'];
        $names = ['Bayu Nugroho', 'Putri Maharani', 'Dimas Anggara', 'Ratna Galih', 'Aditya Wardhana', 'Salsabila Nisa', 'Kevin Ardiansyah', 'Amalia Fitri', 'Reza Pahlevi', 'Kartika Sari'];

        for ($p = 1; $p <= 10; $p++) {
            $accessCode = 'OLM' . str_pad($p, 3, '0', STR_PAD_LEFT);
            $user = User::firstOrCreate(
                ['participant_id' => 'KUAL-00' . $p],
                [
                    'name' => $names[$p - 1],
                    'password' => Hash::make($accessCode),
                    'role' => 'participant',
                    'is_active' => true,
                ]
            );

            $participant = Participant::create([
                'event_id'         => $event->id,
                'user_id'          => $user->id,
                'participant_code' => 'KUAL-00' . $p,
                'access_code'      => $accessCode,
                'institution'      => $institutions[array_rand($institutions)],
                'grade'            => 'Kelas 12',
                'major'            => 'IPA',
                'status'           => 'active',
            ]);

            $round1->participants()->attach($participant->id);

            // Sesi Round 1
            $score1 = rand(40, 100);
            ExamSession::create([
                'participant_id' => $participant->id,
                'round_id' => $round1->id,
                'token' => Str::random(32),
                'started_at' => $round1->start_time->copy()->addMinutes(rand(1, 10)),
                'submitted_at' => $round1->start_time->copy()->addMinutes(rand(30, 50)),
                'status' => 'submitted',
                'score_pg' => $score1,
                'total_score' => $score1,
                'result_status' => 'final',
            ]);

            // Jika lolos passing score (60), masuk ke round 2
            if ($score1 >= 60) {
                $round2->participants()->attach($participant->id);
                // Biarkan pending / belum dikerjakan untuk disimulasikan user
            } else {
                $participant->update(['status' => 'disqualified']); // Gagal kualifikasi
            }
        }
    }
}
