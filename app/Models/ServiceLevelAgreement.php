<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLevelAgreement extends Model
{
    use HasFactory;
    protected $table = 'service_level_agreements';
    protected $fillable = [
        'id',
        'sla_name',
    ];
}
