<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaClientConfig extends Model
{
    use HasFactory;

    protected $table = 'sla_client_configs';

    protected $fillable = [
        'business_entity_id',
        'client_id',
        'resolution_min',
        'sla_status',
        'escalation_status',
    ];

    

    public function businessEntity()
    {
        return $this->belongsTo(Company::class);
    }

    // public function client()
    // {
    //     return $this->belongsTo(Client::class);
    // }

    // public function clientVendor()
    // {
    //     return $this->belongsTo(ClientVendor::class);
    // }
}
