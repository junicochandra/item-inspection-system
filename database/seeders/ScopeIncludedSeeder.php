<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ScopeIncludedSeeder extends Seeder
{
    public function run(): void
    {
        $visual = DB::table('scope_of_works')->where('code', 'VISUAL_INSPECTION')->first();
        $functional = DB::table('scope_of_works')->where('code', 'FUNCTIONAL_TEST')->first();
        $maintenance = DB::table('scope_of_works')->where('code', 'MAINTENANCE_CHECK')->first();
        $preShipment = DB::table('scope_of_works')->where('code', 'PRE_SHIPMENT')->first();

        DB::table('scope_includeds')->insert([

            // VISUAL INSPECTION
            [
                'scope_of_work_id' => $visual->id,
                'name' => 'Surface Condition Check',
                'description' => 'Inspection for scratches, dents, corrosion, and surface damage.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $visual->id,
                'name' => 'Label & Marking Verification',
                'description' => 'Verification of labels, serial numbers, and identification markings.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $visual->id,
                'name' => 'Packaging Integrity Check',
                'description' => 'Assessment of packaging condition and protective materials.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // FUNCTIONAL TEST
            [
                'scope_of_work_id' => $functional->id,
                'name' => 'Operational Performance Test',
                'description' => 'Testing equipment functionality under normal operating conditions.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $functional->id,
                'name' => 'Safety System Verification',
                'description' => 'Verification of safety controls and emergency mechanisms.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $functional->id,
                'name' => 'Calibration Validation',
                'description' => 'Validation of measurement accuracy against standard tolerances.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // MAINTENANCE CHECK
            [
                'scope_of_work_id' => $maintenance->id,
                'name' => 'Wear & Tear Assessment',
                'description' => 'Evaluation of component wear and material degradation.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $maintenance->id,
                'name' => 'Lubrication Inspection',
                'description' => 'Inspection of lubrication systems and fluid levels.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $maintenance->id,
                'name' => 'Preventive Maintenance Recommendation',
                'description' => 'Recommendation for scheduled preventive maintenance actions.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // PRE-SHIPMENT
            [
                'scope_of_work_id' => $preShipment->id,
                'name' => 'Quantity Verification',
                'description' => 'Verification of shipped quantity against packing list.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $preShipment->id,
                'name' => 'Documentation Review',
                'description' => 'Review of shipping documents and compliance certificates.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'scope_of_work_id' => $preShipment->id,
                'name' => 'Final Quality Confirmation',
                'description' => 'Final confirmation of product quality before release.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
