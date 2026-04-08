<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewHelpdesk extends Model
{
    use HasFactory;

    protected $table = 'view_helpdesks';

    protected $fillable = [

        'id',
        'role_id',
        'view_id'
    ];
}
