<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsItem extends Model
{
    use HasFactory;

    protected $table = 'settings_items';

    protected $fillable = [

        'id',
        'name'
    ];
}
