<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $table = 'email_templates';
    protected $fillable = [
        'event_id',
        'business_entity_id',
        'client_id',
        'notify_client',
        'template_name',
        'subject',
        'content',
        'status',
    ];
}
