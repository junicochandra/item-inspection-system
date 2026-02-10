<?php

namespace Database\Factories;

use App\Domains\Item\Models\Item;
use App\Domains\Customer\Models\Customer;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionItemFactory extends Factory
{
    protected $model = InspectionItem::class;

    public function definition(): array
    {
        return [
            'inspection_id' => Inspection::inRandomOrder()->first()->id,
            'item_id' => Item::inRandomOrder()->first()->id,

            'lot_number' => $this->faker->optional()->bothify('LOT-###'),
            'allocation' => $this->faker->optional()->word,

            'owner_id' => Customer::inRandomOrder()->first()?->id,
            'condition_id' => 1,

            'available_qty' => $this->faker->numberBetween(10, 100),
            'required_qty' => $this->faker->numberBetween(1, 10),
            'order_qty' => $this->faker->numberBetween(1, 10),

            'inspection_required' => true,
        ];
    }
}
