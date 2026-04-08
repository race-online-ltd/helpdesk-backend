<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntityCategorySubcategoryMapping extends Model
{
    use HasFactory;

    protected $table = 'entity_category_subcategory_mappings';
    
    protected $fillable = [
        'company_id',
        'category_id',
        'sub_category_id',
        'is_client_visible',
    ];

    /**
     * Get the sub-category associated with this mapping
     */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }

    /**
     * Get the category associated with this mapping
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the company associated with this mapping
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
