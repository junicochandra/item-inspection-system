<?php

namespace App\Domains\Inspection\Repositories;

use Carbon\Carbon;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;

class InspectionRepository
{
    public function getList()
    {
        return Inspection::with(['status'])
            ->withCount('items')
            ->orderByDesc('created_at')
            ->get();
    }

    public function findDetailById(int $id): Inspection
    {
        return Inspection::query()
            ->with([
                'customer',
                'location',
                'status',
                'serviceType',
                'inspectionItems.item',
                'inspectionItems.lot',
                'inspectionItems.allocation',
                'inspectionItems.owner',
                'inspectionItems.condition',
            ])
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        if (empty($data['submitted_at'])) {
            $data['submitted_at'] = Carbon::today()->toDateString();
        }

        return Inspection::create($data);
    }

    public function createInspectionItem(int $inspectionId, array $items)
    {
        $payload = [];

        foreach ($items as $item) {
            $payload[] = [
                'inspection_id' => $inspectionId,
                'lot_id'        => $item['lot_id'],
                'allocation_id' => $item['allocation_id'],
                'owner_id'      => $item['owner_id'],
                'condition_id'  => $item['condition_id'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        return InspectionItem::insert($payload);
    }
}
