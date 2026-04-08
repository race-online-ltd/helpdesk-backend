<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelfTicketOrbit extends Model
{
    use HasFactory;

    protected $table = 'self_ticket_orbits';

    protected $fillable = [
        'ticket_number',
        'client_type',
        'client_id_helpdesk',
        'client_id_vendor',
        'billing_source',
        'sid_uid',
        'fullname',
        'phone',
    ];
}
