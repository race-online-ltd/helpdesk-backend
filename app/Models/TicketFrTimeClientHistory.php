<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFrTimeClientHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_fr_time_client_histories';

    protected $fillable = [
        'ticket_number',
        'client_id',
        'subcat_id',
        'fr_response_id',
        'fr_response_time',
        'fr_response_status_id',
        'fr_response_status_name',
    ];
}
