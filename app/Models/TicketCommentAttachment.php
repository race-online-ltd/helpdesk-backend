<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCommentAttachment extends Model
{
    use HasFactory;
    protected $table = 'ticket_comment_attachments';

    protected $fillable = [
        'comment_id',
        'ticket_number',
        'name',
        'customize_name',
        'size',
        'url',
        'mime_type',
        'storage_type',
    ];
}
