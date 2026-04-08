<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalateFrResAgentSubcategory extends Model
{
    use HasFactory;
    protected $table = 'escalate_fr_res_agent_subcategories';
    protected $fillable = [
        'id',
        'level_id',
        'subcat_id',
        'team_id',
        'agent_id',
    ];
}
