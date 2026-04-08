<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstResConfig extends Model
{
    use HasFactory;
    protected $fillable = [
        'team_id',
        'duration_min',
        'first_response_status',
        'escalation_status',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
