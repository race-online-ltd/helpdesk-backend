<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamSupervisor extends Model
{
    use HasFactory;
    protected $table = 'team_supervisors';
    protected $fillable = [
        'id',
        'agent_id',
        'team_id',
    ];
}
