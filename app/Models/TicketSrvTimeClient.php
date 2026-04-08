<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketSrvTimeClient extends Model
{
    use HasFactory;

    protected $table = 'ticket_srv_time_clients';

    protected $fillable = [
        'ticket_number',
        'client_id',
        'subcat_id',
        'srv_time_id',
        'srv_time_duration',
        'srv_time_status',
        'srv_time_status_name',
    ];
}
