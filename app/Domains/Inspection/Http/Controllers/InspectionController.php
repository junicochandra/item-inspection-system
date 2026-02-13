<?php

namespace App\Domains\Inspection\Http\Controllers;

use App\Domains\Customer\Models\Customer;
use App\Domains\Inspection\Models\InspectionStatus;
use App\Domains\Inspection\Services\InspectionService;
use App\Domains\Location\Models\Location;
use App\Domains\Order\Services\OrderService;
use App\Domains\ServiceType\Models\ServiceType;
use App\Domains\Sow\Models\ScopeOfWork;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function __construct(
        protected InspectionService $service,
        protected OrderService $orderService
    ) {
        //
    }

    public function index(Request $request)
    {
        $statusId = $request->query('status_id', 1);
        return response()->json([
            'data' => $this->service->listInspections($statusId)
        ]);
    }

    public function create()
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
        $errors = $this->service->validateInspectionItems($request->items);
        if (!empty($errors)) {
            return response()->json([
                'status' => 'error',
                'errors' => $errors
            ], 422);
        }

        $inspection = $this->service->createInspection($validated);
        $inspectionItem = $this->service->createInspectionItem($inspection->id, $request->items);

        return response()->json([
            'message' => 'Inspection created successfully',
            'data' => $inspection,
            'inspection_item' => $inspectionItem
        ], 201);
    }

    public function show($id)
    {
        $inspection = $this->service->getInspectionDetail($id);

        return response()->json([
            'data' => $inspection
        ]);
    }

    public function update(Request $request, int $id)
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

            'items' => 'required|array|min:1',
            'items.*.lot_id' => 'required|integer|exists:lots,id',
            'items.*.allocation_id' => 'required|integer',
            'items.*.owner_id' => 'required|integer',
            'items.*.condition_id' => 'required|integer',
            'items.*.qty_required' => 'required|integer|min:1',
        ]);

        $inspection = $this->service->updateInspection($id, $validated, $request->items);

        return response()->json([
            'message' => 'Inspection updated successfully',
            'data' => $inspection
        ]);
    }
}
