<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';
    
    protected $fillable = [
        'sub_category_in_english',
        'sub_category_in_bangla',
    ];

    /**
     * Get the teams associated with this sub-category through SubCategoryTeam
     */
    public function teams()
    {
        return $this->hasMany(SubCategoryTeam::class, 'sub_category_id');
    }

    /**
     * Get the mappings for this sub-category
     */
    public function mappings()
    {
        return $this->hasMany(EntityCategorySubcategoryMapping::class, 'sub_category_id');
    }


    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'entity_category_subcategory_mappings',
            'sub_category_id',
            'category_id'
        );
    }
}
