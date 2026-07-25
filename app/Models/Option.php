<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = false;

    protected $fillable = ['question_id', 'content', 'content_image_url', 'is_correct', 'order_index'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean'];
    }

    public function question() { return $this->belongsTo(Question::class); }
}
