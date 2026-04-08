<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MergeTicket extends Model
{
    use HasFactory;
    protected $table = 'merge_tickets';
    protected $fillable = [
        'ticket_number',
        'child_exists',
        'parent_ticket_number',
        'merged_by',
    ];

}
