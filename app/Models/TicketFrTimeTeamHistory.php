<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFrTimeTeamHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_fr_time_team_histories';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'ticket_number',
        'team_id',
        'subcat_id',
        'fr_response_id',
        'fr_response_time',
        'fr_response_status',
        'fr_response_status_name',
    ];
}
