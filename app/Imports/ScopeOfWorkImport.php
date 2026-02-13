<?php

namespace App\Imports;

use App\Domains\Sow\Models\ScopeOfWork;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ScopeOfWorkImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return ScopeOfWork::updateOrCreate(
            ['code' => strtoupper($row['code'])],
            [
                'name' => $row['name'],
                'description' => $row['description'],
            ]
        );
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
        ];
    }
}
