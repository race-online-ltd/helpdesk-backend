<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryEntityMapping extends Model
{
    use HasFactory;
    
    protected $table = 'category_entity_mappings';
    
    protected $fillable = [
        'company_id',
        'category_id',
        'is_client_visible',
    ];
}
