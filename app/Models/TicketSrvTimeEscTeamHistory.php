<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSrvTimeEscTeamHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_srv_time_esc_team_histories';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'ticket_number',
        'subcat_id',
        'team_id',
        'escalate_id',
        'escalate_level',
        'escalate_srv_response_time',
        'notification_status',
        'status',
        'status_name',
    ];
}
