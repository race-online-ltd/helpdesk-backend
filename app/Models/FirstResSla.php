<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstResSla extends Model
{
    use HasFactory;

    protected $table = 'first_res_slas';

    protected $fillable = [
        'ticket_number',
        'first_res_config_id',
        'sla_status',
    ];

    public function config()
    {
        return $this->belongsTo(FirstResConfig::class, 'first_res_config_id');
    }
}
