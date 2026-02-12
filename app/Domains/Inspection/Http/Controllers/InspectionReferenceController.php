<?php

namespace App\Domains\Inspection\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Customer\Models\Customer;
use App\Domains\Location\Models\Location;
use App\Domains\ServiceType\Models\ServiceType;
use App\Domains\Inspection\Models\InspectionStatus;

class InspectionReferenceController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            'locations' => Location::select('id', 'name')->get(),
            'serviceTypes' => ServiceType::select('id', 'name')->get(),
            'scopeOfWorks' => ScopeOfWork::with(['scopeIncludeds' => function ($q) {
                $q->select('id', 'scope_of_work_id', 'name');
            }])->select('id', 'name')->get(),
            'customers' => Customer::select('id', 'name')->get(),
            'statuses' => InspectionStatus::select('id', 'label')->get(),
        ]);
    }
}
