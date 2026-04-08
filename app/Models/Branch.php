<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branches';
    protected $fillable = [
        'business_entity_id',
        'vendor_client_id',
        'branch_name',
        'mobile1',
        'mobile2',
        'email1',
        'email2',
        'service_address',
    ];
}
