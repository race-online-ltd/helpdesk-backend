<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDivisionDistrict extends Model
{
    use HasFactory;

    protected $table = 'ticket_division_districts';

    protected $fillable = [
        'ticket_number',
        'division',
        'district',
        'thana',
    ];
}
