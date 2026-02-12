<?php

namespace App\Domains\Order\Http\Controllers;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Services\InspectionService;
use App\Domains\Order\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private InspectionService $inspectionService
    ) {
        //
    }

    public function approve(Inspection $inspection)
    {
        DB::transaction(function () use ($inspection, &$orders) {

            $orders = $this->orderService->createFromInspection($inspection);

            $this->inspectionService
                ->updateStatusApproved($inspection->id);

            $this->orderService
                ->updateStock($inspection);
        });

        return response()->json([
            'message' => 'Orders created successfully',
            'data' => $orders
        ]);
    }
}
