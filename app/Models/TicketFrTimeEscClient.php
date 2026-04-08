<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFrTimeEscClient extends Model
{
    use HasFactory;

    protected $table = 'ticket_fr_time_esc_clients';

    protected $fillable = [
        'ticket_number',
        'client_id',
        'subcat_id',
        'team_id',
        'escalate_id',
        'escalate_level',
        'escalate_fr_response_time',
        'notification_status',
        'status',
        'status_name',
    ];
}
