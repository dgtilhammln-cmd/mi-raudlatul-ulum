<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\QuestionBank;
use App\Models\Option;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class QuestionImport implements ToModel, WithHeadingRow
{
    private $bankId;
    public $successCount = 0;

    public function __construct($bankId)
    {
        $this->bankId = $bankId;
    }

    public function model(array $row)
    {
        if (!isset($row['isi_soal']) || empty($row['isi_soal'])) {
            return null;
        }

        DB::transaction(function () use ($row) {
            $typeRaw = strtolower(trim($row['jenis'] ?? 'multiple_choice'));
            $type = 'multiple_choice';
            if (in_array($typeRaw, ['essay', 'esai', 'uraian'])) {
                $type = 'essay';
            }
            
            $diffRaw = strtolower(trim($row['tingkat_kesulitan'] ?? 'medium'));
            $difficulty = 'medium';
            if (in_array($diffRaw, ['easy', 'mudah', 'gampang'])) {
                $difficulty = 'easy';
            } elseif (in_array($diffRaw, ['hard', 'sulit', 'susah'])) {
                $difficulty = 'hard';
            }

            $question = Question::create([
                'bank_id' => $this->bankId,
                'type' => $type,
                'content' => $row['isi_soal'],
                'explanation' => $row['pembahasan'] ?? null,
                'score' => isset($row['skor_benar']) ? (float)$row['skor_benar'] : 10,
                'negative_score' => isset($row['skor_salah']) ? (float)$row['skor_salah'] : 0,
                'difficulty' => $difficulty,
                'category' => $row['kategori'] ?? null,
            ]);

            if ($type === 'multiple_choice') {
                $correctOptionLetter = strtoupper($row['jawaban_benar'] ?? 'A');
                $opts = [
                    'A' => $row['opsi_a'] ?? '',
                    'B' => $row['opsi_b'] ?? '',
                    'C' => $row['opsi_c'] ?? '',
                    'D' => $row['opsi_d'] ?? '',
                    'E' => $row['opsi_e'] ?? '',
                ];

                $index = 0;
                foreach ($opts as $letter => $content) {
                    if (!empty($content)) {
                        $question->options()->create([
                            'content' => (string)$content,
                            'is_correct' => ($letter === $correctOptionLetter),
                            'order_index' => $index,
                        ]);
                        $index++;
                    }
                }
            }

            $this->successCount++;
        });

        return null;
    }
}
