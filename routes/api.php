<?php

use App\Domains\Inspection\Http\Controllers\InspectionController;
use App\Domains\Inspection\Http\Controllers\InspectionReferenceController;
use App\Domains\Inventory\Http\Controllers\LotFilterController;
use App\Domains\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/inspections', [InspectionController::class, 'index']);
Route::get('/inspections/create', [InspectionController::class, 'create']);
Route::post('/inspections', [InspectionController::class, 'store']);
Route::get('/inspections/{id}', [InspectionController::class, 'show']);
Route::post('/inspections/{inspection}/approve', [OrderController::class, 'approve']);

Route::get('/scope-included/{scopeOfWork}', [InspectionController::class, 'getIncluded']);
Route::get('/inspection-references', InspectionReferenceController::class);
Route::get('/lot-filter', [LotFilterController::class, 'index']);
