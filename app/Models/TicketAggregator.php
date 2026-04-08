<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketAggregator extends Model
{
    use HasFactory;
    protected $table = 'ticket_aggregators';
    protected $fillable = [
        'aggregator_id',
        'ticket_number',
    ];
}
