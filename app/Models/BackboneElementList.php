<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackboneElementList extends Model
{
    use HasFactory;
    protected $table = 'backbone_element_lists';

    protected $fillable = [
        'backbone_element_id',
        'name',

    ];
}
