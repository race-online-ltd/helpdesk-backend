<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalateSrvTimeClient extends Model
{
    use HasFactory;
    protected $table = 'escalate_srv_time_clients';
    protected $fillable = [
        'id',
        'business_entity_id',
        'client_id',
        'subcat_id',
        'level_id',
        'notification_min',
        'notification_str',
        'send_email_status',
        'email_template_id',
        'is_deleted',
    ];
}
