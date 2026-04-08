<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportsItem extends Model
{
    use HasFactory;

    protected $table = 'reports_items';

    protected $fillable = [

        'id',
        'name'
    ];
}
