<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCc extends Model
{
    use HasFactory;

    protected $table = 'ticket_ccs';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'ticket_number',
        'agent_id',
        'agent_email',
    ];
}
