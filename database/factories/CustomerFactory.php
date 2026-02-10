<?php

namespace Database\Factories;

use App\Domains\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'code' => $this->faker->unique()->bothify('CUST-###'),
            'description' => $this->faker->sentence(5),
        ];
    }
}
