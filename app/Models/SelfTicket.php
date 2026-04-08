<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfTicket extends Model
{
    use HasFactory;

    protected $table = 'self_tickets';

    protected $fillable = [
        'ticket_number',
        'user_id',
        'status_update_by',
        'business_entity_id',
        'client_id_helpdesk',
        'client_id_vendor',
        'sid',
        'branch_id',
        'source_id',
        'cat_id',
        'subcat_id',
        'priority_name',
        'status_id',
        'team_id',
        'ref_ticket_no',
        'note',
        'attached_filename',
    ];
}
