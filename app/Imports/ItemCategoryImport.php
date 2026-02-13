<?php

namespace App\Imports;

use App\Domains\Master\Models\MasterData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ItemCategoryImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return MasterData::create([
            'type' => $row['type'],
            'name' => $row['name'],
        ]);
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'name' => 'required|string',
        ];
    }
}
