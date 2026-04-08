<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    use HasFactory;
    protected $table = 'ticket_attachments';

    protected $fillable = [
        'ticket_number',
        'name',
        'customize_name',
        'size',
        'url',
        'mime_type',
        'storage_type',
    ];
}
