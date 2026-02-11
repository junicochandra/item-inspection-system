<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Domains\Item\Models\Item;
use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Customer\Models\Customer;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;
use App\Domains\Sow\Models\ScopeIncluded;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            InspectionStatusSeeder::class,
            LocationSeeder::class,
            ServiceTypeSeeder::class,
            ConditionSeeder::class,
        ]);

        // Data Master
        ScopeOfWork::factory(4)->create();
        ScopeIncluded::factory(10)->create();
        Customer::factory(3)->create();
        Item::factory(10)->create();

        // Transactional Data
        Inspection::factory(5)->create();
        InspectionItem::factory(20)->create();
    }
}
