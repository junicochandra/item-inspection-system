<?php

namespace App\Domains\Inspection\Repositories;

use Carbon\Carbon;
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

    public function create(array $data)
    {
        if (empty($data['submitted_at'])) {
            $data['submitted_at'] = Carbon::today()->toDateString();
        }

        return Inspection::create($data);
    }
}
