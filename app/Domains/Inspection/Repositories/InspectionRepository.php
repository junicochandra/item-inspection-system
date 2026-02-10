<?php

namespace App\Domains\Inspection\Repositories;

use App\Domains\Inspection\Models\Inspection;

class InspectionRepository
{
    public function getList()
    {
        return Inspection::with(['status'])
            ->withCount('items')
            ->orderByDesc('created_at')
            ->get();
    }
}
