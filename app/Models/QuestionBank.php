<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBank extends Model
{
    use SoftDeletes;

    protected $fillable = ['event_id', 'name', 'subject', 'description'];

    public function event() { return $this->belongsTo(Event::class); }
    public function questions() { return $this->hasMany(Question::class, 'bank_id'); }

    public function rounds()
    {
        return $this->belongsToMany(Round::class, 'round_banks', 'bank_id', 'round_id')
                    ->withPivot('question_count');
    }
}
