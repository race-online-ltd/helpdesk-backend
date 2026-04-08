<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaClient extends Model
{
    use HasFactory;
    protected $table = 'sla_clients';
    protected $fillable = [
        'id',
        'sla_id',
        'business_entity_id',
        'client_id',
        'subcat_id',
        'fr_res_day',
        'fr_res_hr',
        'fr_res_min',
        'fr_res_time_min',
        'fr_res_time_str',
        'srv_day',
        'srv_hr',
        'srv_min',
        'srv_time_min',
        'srv_time_str',
        'esc_status',
        'status',
    ];

    // protected $casts = [
    //     'esc_status' => 'boolean',
    //     'status' => 'boolean',
    // ];
}
