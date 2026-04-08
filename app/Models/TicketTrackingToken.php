<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTrackingToken extends Model
{
    use HasFactory;
    protected $table = 'ticket_tracking_tokens';

    protected $fillable = [
        'ticket_number',
        'token',
        'tracking_url',
    ];
}
