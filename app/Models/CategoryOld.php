<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// class Category extends Model
// {
//     use HasFactory;
//     protected $table = 'categories';
//     protected $fillable = [
//         'company_id',
//         'category_in_english',
//         'category_in_bangla',
//     ];
// }




class Category extends Model
{
    use HasFactory;
    
    protected $table = 'categories_old';
    
    protected $fillable = [
        'company_id',
        'category_in_english',
        'category_in_bangla',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'category_companies', 'category_id', 'company_id')
                    ->withTimestamps();
    }
    
    
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'category_teams', 'category_id', 'team_id')
                    ->withTimestamps();
    }
}
