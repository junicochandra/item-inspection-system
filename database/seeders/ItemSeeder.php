<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('items')->insert([
            [
                'code' => 'MTM30624701',
                'description' => '17 PPF, JFE-13CR-80, PSL1, TSH BLUE, R3'
            ],
        ]);
    }
}
