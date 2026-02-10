<?php

namespace Database\Factories;

use App\Domains\Item\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('ITEM-###'),
            'description' => $this->faker->sentence(4),
        ];
    }
}
