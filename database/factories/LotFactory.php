<?php

namespace Database\Factories;

use App\Domains\Lot\Models\Lot;
use App\Domains\Item\Models\Item;
use App\Domains\Master\Models\MasterData;
use Illuminate\Database\Eloquent\Factories\Factory;

class LotFactory extends Factory
{
    protected $model = Lot::class;
    public function definition(): array
    {
        $item = Item::inRandomOrder()->first() ?? Item::factory()->create();

        $allocation = MasterData::where('type', 'allocation')->inRandomOrder()->first();
        $owner = MasterData::where('type', 'owner')->inRandomOrder()->first();
        $condition = MasterData::where('type', 'condition')->inRandomOrder()->first();

        return [
            'item_id' => $item->id,
            'allocation_id' => $allocation ? $allocation->id : null,
            'owner_id' => $owner ? $owner->id : null,
            'condition_id' => $condition ? $condition->id : null,
            'lot_no' => strtoupper($this->faker->bothify('LOT-###')),
        ];
    }
}
