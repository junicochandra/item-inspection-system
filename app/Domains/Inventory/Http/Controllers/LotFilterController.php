<?php

namespace App\Domains\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Domains\Inventory\Services\LotFilterService;

class LotFilterController extends Controller
{
    public function __construct(
        protected LotFilterService $service
    ) {
    }

    public function index(Request $request)
    {
        return response()->json(
            $this->service->getFilteredOptions(
                $request->filled('lot_id') ? (int) $request->lot_id : null,
                $request->filled('allocation_id') ? (int) $request->allocation_id : null,
                $request->filled('owner_id') ? (int) $request->owner_id : null,
                $request->filled('condition_id') ? (int) $request->condition_id : null,
            )
        );
    }
}
