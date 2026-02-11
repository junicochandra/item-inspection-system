<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $masterData = [
            ['type' => 'allocation', 'name' => 'Warehouse A'],
            ['type' => 'allocation', 'name' => 'Warehouse B'],
            ['type' => 'allocation', 'name' => 'Rack 1'],
            ['type' => 'allocation', 'name' => 'Rack 2'],
            ['type' => 'allocation', 'name' => 'Warehouse C'],

            ['type' => 'owner', 'name' => 'PT. ABC'],
            ['type' => 'owner', 'name' => 'PT. XYZ'],
            ['type' => 'owner', 'name' => 'PT. QWE'],
            ['type' => 'owner', 'name' => 'PT. LMN'],
            ['type' => 'owner', 'name' => 'PT. RST'],

            ['type' => 'condition', 'name' => 'New'],
            ['type' => 'condition', 'name' => 'Damaged'],
            ['type' => 'condition', 'name' => 'Light Damage'],
            ['type' => 'condition', 'name' => 'Expired'],
            ['type' => 'condition', 'name' => 'Recondition'],
        ];

        DB::table('master_data')->insert($masterData);
    }
}
