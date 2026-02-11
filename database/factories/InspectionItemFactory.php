<?php

namespace Database\Factories;

use App\Domains\Lot\Models\Lot;
use App\Domains\Item\Models\Item;
use App\Domains\Master\Models\MasterData;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionItemFactory extends Factory
{
    protected $model = InspectionItem::class;

    public function definition(): array
    {
        $item = Item::inRandomOrder()->first() ?? Item::factory()->create();
        $lot = Lot::where('item_id', $item->id)->inRandomOrder()->first() ?? Lot::factory()->create(['item_id' => $item->id]);

        return [
            'inspection_id' => Inspection::factory(),
            'item_id' => $item->id,
            'lot_id' => $lot->id,
            'allocation_id' => MasterData::where('type', 'allocation')->inRandomOrder()->first()->id ?? null,
            'owner_id' => MasterData::where('type', 'owner')->inRandomOrder()->first()->id ?? null,
            'condition_id' => MasterData::where('type', 'condition')->inRandomOrder()->first()->id ?? null,
        ];
    }
}
