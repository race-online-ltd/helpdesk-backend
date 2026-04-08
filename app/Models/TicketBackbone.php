<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketBackbone extends Model
{
    use HasFactory;
    protected $table = 'ticket_backbones';

    protected $fillable = [
        'ticket_number',
        'backbone_element_id',
        'backbone_element_list_id',
        'backbone_element_list_id_a_end',
        'backbone_element_list_id_b_end',
    ];
}
