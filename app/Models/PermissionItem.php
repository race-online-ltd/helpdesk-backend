<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionItem extends Model
{
    use HasFactory;

    protected $table = 'permission_items';

    protected $fillable = [

        'id',
        'name'
    ];
}
