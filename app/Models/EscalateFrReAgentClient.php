<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalateFrReAgentClient extends Model
{
    use HasFactory;
    protected $table = 'escalate_fr_re_agent_clients';
    protected $fillable = [
        'id',
        'level_id',
        'subcat_id',
        'agent_id',
    ];
}
