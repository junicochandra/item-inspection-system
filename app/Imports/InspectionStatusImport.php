<?php

namespace App\Imports;

use App\Domains\Inspection\Models\InspectionStatus;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InspectionStatusImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return InspectionStatus::updateOrCreate(
            ['code' => strtoupper($row['code'])],
            ['label' => $row['label']]
        );
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string',
            'label' => 'required|string',
        ];
    }
}
