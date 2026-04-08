<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAggregatorMapping extends Model
{
    use HasFactory;

    protected $table = 'client_aggregator_mappings';

    protected $fillable = [
        'business_entity_id',
        'client_id',
        'aggregator_id',
    ];

    // 🔹 Relation
    public function aggregator()
    {
        return $this->belongsTo(Aggregator::class, 'aggregator_id');
    }
}
