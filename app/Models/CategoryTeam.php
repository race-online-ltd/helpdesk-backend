<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryTeam extends Model
{
    use HasFactory;
    protected $table = 'category_teams';
    protected $fillable = [
        'id',
        'category_id',
        'team_id',
    ];
}
