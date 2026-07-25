<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'bank_id', 'type', 'content', 'content_image_url',
        'explanation', 'score', 'negative_score', 'difficulty', 'category',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'negative_score' => 'decimal:2',
        ];
    }

    public function bank() { return $this->belongsTo(QuestionBank::class, 'bank_id'); }
    public function options() { return $this->hasMany(Option::class)->orderBy('order_index'); }

    public function isMultipleChoice(): bool { return $this->type === 'multiple_choice'; }
    public function isEssay(): bool { return $this->type === 'essay'; }

    public function correctOption()
    {
        return $this->hasOne(Option::class)->where('is_correct', true);
    }
}
