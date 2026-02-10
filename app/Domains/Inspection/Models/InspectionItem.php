<?php

namespace App\Domains\Inspection\Models;

use Illuminate\Database\Eloquent\Model;
use Database\Factories\InspectionItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'inspection_id',
        'item_id',
        'lot_number',
        'allocation',
        'owner_id',
        'condition_id',
        'available_qty',
        'required_qty',
        'order_qty',
        'inspection_required'
    ];

    protected static function newFactory()
    {
        return InspectionItemFactory::new();
    }
}
