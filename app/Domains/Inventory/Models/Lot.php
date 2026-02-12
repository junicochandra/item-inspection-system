<?php

namespace App\Domains\Inventory\Models;

use Database\Factories\LotFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Master\Models\MasterData;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lot extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return LotFactory::new();
    }

    protected $fillable = [
        'item_id',
        'lot_no',
        'allocation_id',
        'owner_id',
        'condition_id',
        'qty',
        'price',
    ];

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
