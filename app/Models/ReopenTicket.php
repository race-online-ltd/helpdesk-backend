<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReopenTicket extends Model
{
    use HasFactory;
    protected $table = 'reopen_tickets';
    protected $fillable = [
        'ticket_number',
        'reopened_by',
        'note',
    ];
}
