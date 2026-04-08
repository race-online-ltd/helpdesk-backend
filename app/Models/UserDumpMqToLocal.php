<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDumpMqToLocal extends Model
{
    use HasFactory;

    protected $table = 'user_dump_mq_to_locals';
    protected $fillable = [
        'sid',
        'pppoe_name',
        'entity_name',
        'entity_id',
        'entity_code',
        'entity_type',
        'full_name',
        'email',
        'phone',
        'password',
    ];
}
