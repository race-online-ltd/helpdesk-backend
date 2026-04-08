<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;


    protected $table = 'user_profiles';

     protected $fillable = [
        'user_type',
        'user_id',
        'fullname',
        'email_primary',
        'email_secondary',
        'mobile_primary',
        'mobile_secondary',
        'role_id',
        'default_entity_id',
        'one_time_password',
        'password_change',
        'status',
    ];
}
