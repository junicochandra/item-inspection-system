<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Inspection\Http\Controllers\InspectionController;

Route::get('/inspections', [InspectionController::class, 'index']);
