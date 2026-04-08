<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrvTimeClientSlaHistory extends Model
{
    use HasFactory;
    
    protected $table = 'srv_time_client_sla_histories';

    public $timestamps = false;

    protected $fillable = [
        'ticket_number',
        'sla_client_config_id',
        'sla_status',
    ];
}
