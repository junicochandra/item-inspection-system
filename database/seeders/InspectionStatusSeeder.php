<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InspectionStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['code' => 'OPEN', 'label' => 'Open'],
            ['code' => 'FOR_REVIEW', 'label' => 'For Review'],
            ['code' => 'COMPLETED', 'label' => 'Completed'],
        ];

        DB::table('inspection_statuses')->insert($statuses);
    }
}
