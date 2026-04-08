<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionHelpdesk extends Model
{
    use HasFactory;

    protected $table = 'permission_helpdesks';

    protected $fillable = [

        'id',
        'permission_id',
        'role_id',
        'view_id'
    ];
}
