<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalateSrvTimeAgentClient extends Model
{
    use HasFactory;
    protected $table = 'escalate_srv_time_agent_clients';
    protected $fillable = [
        'id',
        'level_id',
        'subcat_id',
        'agent_id',
    ];
}
