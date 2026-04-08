<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMappingForPartner extends Model
{
    use HasFactory;

    protected $table = 'team_mapping_for_partners';

    protected $fillable = [
        'company_id',
        'category_id',
        'subcategory_id',
        'team_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship: Team Mapping belongs to Company
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Relationship: Team Mapping belongs to Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship: Team Mapping belongs to SubCategory
     */
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    /**
     * Relationship: Team Mapping belongs to Team
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
