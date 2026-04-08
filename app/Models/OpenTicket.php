<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenTicket extends Model
{
     use HasFactory;

    protected $table = 'open_tickets';

    protected $fillable = [
        'ticket_number',
        'is_parent',
        'platform_id',
        'user_id',
        'status_updated_by',
        'assigned_agent_id',
        'business_entity_id',
        'client_id_helpdesk',
        'client_id_vendor',
        'source_id',
        'cat_id',
        'subcat_id',
        'status_id',
        'team_id',
        'priority_name',
        'note',
        'mobile_no',
    ];
}