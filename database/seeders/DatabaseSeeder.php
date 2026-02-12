<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Domains\Item\Models\Item;
use App\Domains\Inventory\Models\Lot;
use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Customer\Models\Customer;
use App\Domains\Sow\Models\ScopeIncluded;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;
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
            MasterDataSeeder::class,
            ScopeOfWorkSeeder::class,
            ScopeIncludedSeeder::class,
        ]);

        // Data Master
        Customer::factory(10)->create();

        // Items & lots
        Item::factory()
            ->count(5)
            ->create()
            ->each(function ($item) {
                Lot::factory()->count(3)->create(['item_id' => $item->id]);
            });

        // Transactional Data
        Inspection::factory()->count(50)->create()->each(function ($inspection) {
            // 1-3 items per inspection
            foreach (Item::inRandomOrder()->take(rand(1, 3))->get() as $item) {
                $lot = Lot::where('item_id', $item->id)->inRandomOrder()->first();
                InspectionItem::factory()->create([
                    'inspection_id' => $inspection->id,
                    'item_id' => $item->id,
                    'lot_id' => $lot->id,
                ]);
            }
        });
    }
}
