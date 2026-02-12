<?php

namespace App\Domains\Inspection\Repositories;

use Carbon\Carbon;
use App\Domains\Inventory\Models\Lot;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Models\InspectionItem;

class InspectionRepository
{
    public function getList(int $statusId)
    {
        return Inspection::with([
            'status',
            'location',
            'scopeOfWork',
            'serviceType'
            ])
            ->withCount('items')
            ->when($statusId, function ($query, $statusId) {
                $query->where('status_id', $statusId);
            })
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
                'scopeOfWork:id,name',
                'scopeOfWork.scopeIncludeds:id,scope_of_work_id,name,description',
                'inspectionItems.item',
                'inspectionItems.lot',
                'inspectionItems.allocation',
                'inspectionItems.owner',
                'inspectionItems.condition',
                'inspectionItems.item',
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
        $lotIds = collect($items)->pluck('lot_id')->unique();
        $lots = Lot::whereIn('id', $lotIds)
            ->get(['id', 'item_id', 'qty', 'price'])
            ->keyBy('id');
        $payload = [];

        foreach ($items as $item) {
            $lot = $lots[$item['lot_id']] ?? null;

            if (!$lot) {
                throw new \Exception("Lot ID {$item['lot_id']} not found.");
            }

            $payload[] = [
                'inspection_id' => $inspectionId,
                'item_id'       => $lot->item_id,
                'lot_id'        => $lot->id,
                'allocation_id' => $item['allocation_id'],
                'owner_id'      => $item['owner_id'],
                'condition_id'  => $item['condition_id'],
                'qty_required'  => $item['qty_required'],
                'initial_stock' => $lot->qty,
                'price'         => $lot->price,
                'subtotal'      => $item['qty_required'] * $lot->price,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
        }

        return InspectionItem::insert($payload);
    }
}
