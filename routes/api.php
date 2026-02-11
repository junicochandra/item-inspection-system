<?php

use Illuminate\Support\Facades\Route;
use App\Domains\Inventory\Http\Controllers\LotFilterController;
use App\Domains\Inspection\Http\Controllers\InspectionController;
use App\Domains\Inspection\Http\Controllers\InspectionReferenceController;

Route::get('/inspections', [InspectionController::class, 'index']);
Route::post('/inspections', [InspectionController::class, 'store']);
Route::get('/scope-included/{scopeOfWork}', [InspectionController::class, 'getIncluded']);

Route::get('/inspection-references', InspectionReferenceController::class);
Route::get('/lot-filter', [LotFilterController::class, 'index']);
