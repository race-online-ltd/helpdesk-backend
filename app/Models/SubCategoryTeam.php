<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryTeam extends Model
{
    use HasFactory;
    protected $table = 'sub_category_teams';
    protected $fillable = [
        'id',
        'category_id',
        'sub_category_id',
        'team_id',
    ];
}
