<?php

namespace App\Imports;

use App\Domains\Sow\Models\ScopeIncluded;
use App\Domains\Sow\Models\ScopeOfWork;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ScopeOfWorkItemImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        $scope = ScopeOfWork::where('code', strtoupper($row['scope_code']))->first();

        if (!$scope) {
            return null;
        }

        return ScopeIncluded::create([
            'scope_of_work_id' => $scope->id,
            'name' => $row['name'] ?? null,
            'description' => $row['description'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'scope_code' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
