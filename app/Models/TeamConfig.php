<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamConfig extends Model
{
     use HasFactory;

    protected $table = 'team_configs';

   protected $fillable = [
        'team_id',
        'sla_hold_agents',
        'reopen_agents',
        'merge_agents',
        'escalate_agents',
        'additional_emails',
    ];

    protected $casts = [
        'sla_hold_agents' => 'array',
        'reopen_agents' => 'array',
        'merge_agents' => 'array',
        'escalate_agents' => 'array',
        'additional_emails' => 'array',
    ];


    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
