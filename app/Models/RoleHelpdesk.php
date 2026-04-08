<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHelpdesk extends Model
{
    use HasFactory;

    protected $table = 'role_helpdesks';

    protected $fillable = [

        'default_type',
        'name'
    ];
}
