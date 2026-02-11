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
        // Ambil data scope included berdasarkan scope_of_work_id
        $scopeOfWork = ScopeOfWork::with('scopeIncludeds')->find($scopeOfWorkId);

        if (!$scopeOfWork) {
            return response()->json([
                'success' => false,
                'message' => 'Scope of Work not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $scopeOfWork->scopeIncludeds // relasi hasMany
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_no' => 'required|string|unique:inspections,request_no',

            'location_id' => 'required|integer|exists:locations,id',
            'service_type_id' => 'required|integer|exists:service_types,id',
            'scope_of_work_id' => 'required|integer|exists:scope_of_works,id',
            'customer_id' => 'nullable|integer|exists:customers,id',

            'submitted_at' => 'nullable|date',
            'estimated_completion_date' => 'nullable|date',

            'related_to' => 'nullable|string|max:255',
            'charge_to_customer' => 'boolean',

            'status_id' => 'required|integer|exists:inspection_statuses,id',
            'note' => 'nullable|string',
        ]);

        $inspection = $this->service->createInspection($validated);

        return response()->json([
            'message' => 'Inspection created successfully',
            'data' => $inspection
        ], 201);
    }
}
