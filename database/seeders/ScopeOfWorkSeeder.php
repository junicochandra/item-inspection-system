<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScopeOfWorkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('scope_of_works')->insert([
            [
                'code' => 'VISUAL_INSPECTION',
                'name' => 'Visual Inspection',
                'description' => 'Non-destructive inspection focusing on physical appearance and visible defects.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FUNCTIONAL_TEST',
                'name' => 'Functional Testing',
                'description' => 'Inspection to verify operational performance and functionality.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MAINTENANCE_CHECK',
                'name' => 'Maintenance Inspection',
                'description' => 'Routine inspection to evaluate wear, condition, and preventive maintenance needs.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'PRE_SHIPMENT',
                'name' => 'Pre-Shipment Inspection',
                'description' => 'Final inspection before goods are released for shipment.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
