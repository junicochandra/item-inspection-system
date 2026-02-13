<?php

use App\Domains\Import\Http\Controllers\ImportController;
use App\Domains\Inspection\Http\Controllers\InspectionController;
use App\Domains\Inspection\Http\Controllers\InspectionReferenceController;
use App\Domains\Inventory\Http\Controllers\LotFilterController;
use App\Domains\Order\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/inspections', [InspectionController::class, 'index']);
Route::get('/inspections/create', [InspectionController::class, 'create']);
Route::post('/inspections', [InspectionController::class, 'store']);
Route::put('/inspections/{id}', [InspectionController::class, 'update']);
Route::get('/inspections/{id}', [InspectionController::class, 'show']);
Route::post('/inspections/{inspection}/approve', [OrderController::class, 'approve']);

Route::get('/scope-included/{scopeOfWork}', [InspectionController::class, 'getIncluded']);
Route::get('/inspection-references', InspectionReferenceController::class);
Route::get('/lot-filter', [LotFilterController::class, 'index']);

// import
Route::post('/import/inspection-statuses', [ImportController::class, 'importInspectionStatuses']);
Route::post('/import/scope-of-works', [ImportController::class, 'importScopeOfWork']);
Route::post('/import/scope-of-work-items', [ImportController::class, 'importScopeOfWorkItems']);
Route::post('/import/item-categories', [ImportController::class, 'importItemCategory']);
