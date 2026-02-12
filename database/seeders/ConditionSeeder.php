<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('conditions')->insert([
            [
                'name' => 'New',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Good',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Minor Defect',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Major Defect',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Damaged',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recondition',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Under Repair',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quarantine',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Scrap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
