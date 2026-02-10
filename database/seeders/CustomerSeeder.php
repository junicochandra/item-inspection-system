<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            ['name' => 'PT Santosa', 'code' => 'CUST-001', 'description' => 'Customer Santosa'],
            ['name' => 'PT Nusantara', 'code' => 'CUST-002', 'description' => 'Customer Nusantara'],
        ]);
    }
}
