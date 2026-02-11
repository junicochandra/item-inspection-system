<?php

namespace Database\Factories;

use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Sow\Models\ScopeIncluded;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScopeIncludedFactory extends Factory
{
    protected $model = ScopeIncluded::class;

    public function definition(): array
    {
        return [
            'scope_of_work_id' => function () {
                return ScopeOfWork::inRandomOrder()->value('id');
            },
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'is_active' => true,
        ];
    }
}
