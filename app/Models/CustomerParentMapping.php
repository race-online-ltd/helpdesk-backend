<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerParentMapping extends Model
{
    use HasFactory;

    protected $table = 'customer_parent_mappings';
    protected $fillable = [
        'client_id',
        'client_name',
        'user_id'
    ];
}
