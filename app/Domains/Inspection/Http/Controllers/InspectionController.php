<?php

namespace App\Domains\Inspection\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Sow\Models\ScopeOfWork;
use App\Domains\Inspection\Services\InspectionService;

class InspectionController extends Controller
{
    public function __construct(
        protected InspectionService $service
    ) {
        //
    }

    public function index()
    {
        return response()->json([
            'data' => $this->service->listInspections()
        ]);
    }

    public function getIncluded($scopeOfWorkId)
    {
        $scopeOfWork = ScopeOfWork::with('scopeIncludeds')->find($scopeOfWorkId);

        if (!$scopeOfWork) {
            return response()->json([
                'success' => false,
                'message' => 'Scope of Work not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $scopeOfWork->scopeIncludeds
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_id' => 'required|integer|exists:locations,id',
            'service_type_id' => 'required|integer|exists:service_types,id',
            'scope_of_work_id' => 'required|integer|exists:scope_of_works,id',
            'customer_id' => 'required|integer|exists:customers,id',

            'submitted_at' => 'nullable|date',
            'estimated_completion_date' => 'required|date',

            'related_to' => 'nullable|string|max:255',
            'charge_to_customer' => 'boolean',

            'status_id' => 'required|integer|exists:inspection_statuses,id',
            'note' => 'nullable|string',
        ]);

        $validated['request_no'] = $this->service->generateRequestNo();
        $inspection = $this->service->createInspection($validated);
        $inspectionItem = $this->service->createInspectionItem($inspection->id, $request->items);

        return response()->json([
            'message' => 'Inspection created successfully',
            'data' => $inspection,
            'inspection_item' => $inspectionItem
        ], 201);
    }
}
