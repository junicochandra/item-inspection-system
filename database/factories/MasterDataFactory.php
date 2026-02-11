<?php

namespace Database\Factories;

use App\Domains\Master\Models\MasterData;
use Illuminate\Database\Eloquent\Factories\Factory;

class MasterDataFactory extends Factory
{
    protected $model = MasterData::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['allocation', 'owner', 'condition']),
            'name' => $this->faker->word(),
        ];
    }
}
