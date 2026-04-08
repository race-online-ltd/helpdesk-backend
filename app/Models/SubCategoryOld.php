<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryOld extends Model
{
    use HasFactory;
    protected $table = 'sub_categories_old';
    protected $fillable = [
        'company_id',
        'category_id',
        'team_id',
        'sub_category_in_english',
        'sub_category_in_bangla',
    ];
}
