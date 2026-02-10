<?php

namespace App\Domains\Inspection\Http\Controllers;

use App\Http\Controllers\Controller;
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
}
