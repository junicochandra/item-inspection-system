<?php

namespace Database\Factories;

use App\Domains\Sow\Models\ScopeOfWork;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScopeOfWorkFactory extends Factory
{
    protected $model = ScopeOfWork::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->lexify('??')),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
        ];
    }
}
