<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBranch extends Model
{
    use HasFactory;

    protected $table = 'ticket_branches';

    protected $fillable = [
        'ticket_number',
        'branch_id',
    ];

}
