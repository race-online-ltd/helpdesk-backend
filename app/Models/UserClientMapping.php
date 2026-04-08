<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClientMapping extends Model
{
    use HasFactory;

    protected $table = 'user_client_mappings';

    protected $fillable = [
        'client_id',
        'client_name',
        'business_entity_id',
        'user_id'
    ];
}
