<?php

namespace App\Domains\Order\Models;

use App\Domains\Inspection\Models\InspectionItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];

    public function inspectionItem()
    {
        return $this->belongsTo(InspectionItem::class);
    }
}
