<?php

namespace App\Domains\Import\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Imports\InspectionStatusImport;
use App\Imports\ItemCategoryImport;
use App\Imports\ScopeOfWorkImport;
use App\Imports\ScopeOfWorkItemImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importInspectionStatuses(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        DB::transaction(function () use ($request) {
            Excel::import(
                new InspectionStatusImport(),
                $request->file('file')
            );
        });

        return response()->json([
            'message' => 'Inspection statuses imported successfully'
        ]);
    }

    public function importScopeOfWork(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        DB::transaction(function () use ($request) {
            Excel::import(
                new ScopeOfWorkImport(),
                $request->file('file')
            );
        });

        return response()->json([
            'message' => 'Scope of work imported successfully'
        ]);
    }

    public function importScopeOfWorkItems(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        DB::transaction(function () use ($request) {
            Excel::import(
                new ScopeOfWorkItemImport(),
                $request->file('file')
            );
        });

        return response()->json([
            'message' => 'Scope of work items imported successfully'
        ]);
    }

    public function importItemCategory(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        DB::transaction(function () use ($request) {
            Excel::import(
                new ItemCategoryImport(),
                $request->file('file')
            );
        });

        return response()->json([
            'message' => 'Item categories imported successfully'
        ]);
    }
}
