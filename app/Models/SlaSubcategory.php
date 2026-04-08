<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaSubcategory extends Model
{
    use HasFactory;
    protected $table = 'sla_subcategories';
    protected $fillable = [
        'id',
        'sla_id',
        'business_entity_id',
        'team_id',
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
}
