<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackboneElement extends Model
{
    use HasFactory;
    protected $table = 'backbone_elements';

    protected $fillable = [
        'name',
    ];
}
