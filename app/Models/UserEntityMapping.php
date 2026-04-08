<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEntityMapping extends Model
{
    use HasFactory;

    protected $table = 'user_entity_mappings';

    protected $fillable = [

        'id',
        'business_entity_id',
        'user_id'
    ];
}
