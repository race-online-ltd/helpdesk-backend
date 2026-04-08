<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAssignTeamLog extends Model
{
    use HasFactory;

    protected $table = 'ticket_assign_team_logs';

    protected $fillable = [
        'ticket_number',
        'assigned_in',
        'assigned_out',
    ];
    
}
