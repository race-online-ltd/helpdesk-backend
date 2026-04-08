<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEntityWiseClient extends Model
{
    use HasFactory;
    
    protected $table = 'business_entity_wise_clients';

    protected $fillable = [
        'business_entity_id',
        'client_id',
        'client_name',
    ];

}
