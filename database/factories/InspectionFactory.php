<?php

namespace Database\Factories;

use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Customer\Models\Customer;
use App\Domains\Location\Models\Location;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\ServiceType\Models\ServiceType;
use App\Domains\Inspection\Models\InspectionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class InspectionFactory extends Factory
{
    protected $model = Inspection::class;
    public function definition(): array
    {
        return [
            'request_no' => $this->faker->unique()->bothify('REQ-#####'),

            'location_id' => Location::inRandomOrder()->first()->id,
            'service_type_id' => ServiceType::inRandomOrder()->first()->id,
            'scope_of_work_id' => ScopeOfWork::inRandomOrder()->first()->id,
            'customer_id' => Customer::inRandomOrder()->first()?->id,

            'submitted_at' => now(),
            'estimated_completion_date' => now()->addDays(3),

            'related_to' => $this->faker->optional()->sentence(3),
            'charge_to_customer' => $this->faker->boolean,

            'status_id' => InspectionStatus::inRandomOrder()->first()->id,
            'note' => $this->faker->optional()->paragraph,
        ];
    }
}
