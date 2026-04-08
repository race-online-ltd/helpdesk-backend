<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientChildMapping extends Model
{
    use HasFactory;

    protected $table = 'client_child_mappings';

    protected $fillable = [
        'id',
        'business_entity_id',
        'client_id_helpdesk',
        'client_id_vendor',
        'client_name',
    ];
}
