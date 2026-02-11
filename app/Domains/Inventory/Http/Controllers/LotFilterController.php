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
                $request->lot_id,
                $request->allocation_id,
                $request->owner_id,
                $request->condition_id
            )
        );
    }
}
