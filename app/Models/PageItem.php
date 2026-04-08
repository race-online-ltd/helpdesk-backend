<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageItem extends Model
{
    use HasFactory;

    protected $table = 'page_items';

    protected $fillable = [

        'sidebar_id',
        'name'
    ];
}
