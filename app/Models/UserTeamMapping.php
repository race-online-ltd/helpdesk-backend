<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTeamMapping extends Model
{
    use HasFactory;

    protected $table = 'user_team_mappings';

    protected $fillable = [
        'user_id',
        'team_id'
    ];
}
