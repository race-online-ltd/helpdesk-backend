<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SrvTimeSubcatSla extends Model
{
    use HasFactory;

     protected $table = 'srv_time_subcat_slas';

    protected $fillable = [
        'ticket_number',
        'sla_subcat_config_id',
        'sla_status',
    ];

   
    public function slaSubcatConfig()
    {
        return $this->belongsTo(SlaSubcatConfig::class);
    }
}
