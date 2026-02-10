<?php

namespace App\Domains\Inspection\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\InspectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inspection extends Model
{
    use HasFactory;
    protected $fillable = [
        'request_no',
        'location_id',
        'service_type_id',
        'scope_of_work_id',
        'customer_id',
        'submitted_at',
        'estimated_completion_date',
        'related_to',
        'charge_to_customer',
        'status_id',
        'note',
    ];

    protected static function newFactory()
    {
        return InspectionFactory::new();
    }

    public function items()
    {
        return $this->hasMany(InspectionItem::class);
    }

    public function status()
    {
        return $this->belongsTo(InspectionStatus::class);
    }
}
