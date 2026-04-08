<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

   protected $table = 'teams';

    /**
     * Mass assignable fields
     */
    protected $fillable = [
        'team_name',
        'group_email',
        'additional_email',
        'department_id',
        'division_id',
        'idle_start_hr',
        'idle_start_min',
        'idle_end_hr',
        'idle_end_min',
        'idle_start_end_diff_min',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'additional_email' => 'array',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_teams',
            'team_id',
            'category_id'
        );
    }

    public function subCategoryTeams()
    {
        return $this->hasMany(SubCategoryTeam::class, 'team_id');
    }
}
