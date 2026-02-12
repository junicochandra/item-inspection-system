<?php

namespace App\Domains\Inspection\Models;

use App\Domains\Item\Models\Item;
use App\Domains\Inventory\Models\Lot;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Master\Models\MasterData;
use Database\Factories\InspectionItemFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InspectionItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected static function newFactory()
    {
        return InspectionItemFactory::new();
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function lot()
    {
        return $this->belongsTo(Lot::class);
    }

    public function allocation()
    {
        return $this->belongsTo(MasterData::class, 'allocation_id');
    }

    public function owner()
    {
        return $this->belongsTo(MasterData::class, 'owner_id');
    }

    public function condition()
    {
        return $this->belongsTo(MasterData::class, 'condition_id');
    }
}
