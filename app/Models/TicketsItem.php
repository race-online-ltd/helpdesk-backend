<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketsItem extends Model
{
    use HasFactory;

    protected $table = 'tickets_items';

    protected $fillable = [

        'id',
        'name'
    ];
}
