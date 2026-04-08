<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstResSlaHistory extends Model
{
    use HasFactory;
    protected $table = 'first_res_sla_histories';

    public $timestamps = false;

    protected $fillable = [
        'ticket_number',
        'first_res_config_id',
        'sla_status',
        'created_at',
        'updated_at',
    ];
}
