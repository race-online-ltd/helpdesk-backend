<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlaSubcatConfig extends Model
{
    use HasFactory;

    protected $table = 'sla_subcat_configs';

    protected $fillable = [
        'business_entity_id',
        'team_id',
        'subcategory_id',
        'resolution_min',
        'sla_status',
        'escalation_status',
    ];

    

    public function businessEntity()
    {
        return $this->belongsTo(Company::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
}
