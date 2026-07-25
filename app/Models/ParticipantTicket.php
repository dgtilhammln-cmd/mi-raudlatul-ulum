<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'wa_number',
        'needs',
        'message',
        'status',
    ];
}
