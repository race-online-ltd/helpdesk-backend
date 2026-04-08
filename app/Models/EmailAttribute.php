<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailAttribute extends Model
{
    use HasFactory;
    protected $table = 'email_attributes';
    protected $fillable = [
        'name',
        'value',
    ];
    
}
