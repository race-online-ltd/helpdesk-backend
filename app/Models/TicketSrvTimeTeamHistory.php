<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSrvTimeTeamHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_srv_time_team_histories';

    
    protected $fillable = [
        'ticket_number',
        'team_id',
        'subcat_id',
        'srv_time_id',
        'srv_time_duration',
        'srv_time_status',
        'srv_time_status_name',
    ];
}
