<?php

namespace App\Domains\Inspection\Services;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Repositories\InspectionRepository;
use App\Domains\Inventory\Models\Lot;
use Illuminate\Support\Facades\DB;

class InspectionService
{
    public function __construct(
        protected InspectionRepository $repository
    ) {
        //
    }

    public function listInspections($statusId)
    {
        return $this->repository->getList($statusId);
    }

    public function getInspectionDetail(int $id)
    {
        $inspection = $this->repository->findDetailById($id);

        return $inspection;
    }

    public function createInspection(array $data)
    {
        return $this->repository->create($data);
    }

    public function createInspectionItem($inspectionId, $items)
    {
        return $this->repository->createInspectionItem($inspectionId, $items);
    }

    public function generateRequestNo()
    {
        return DB::transaction(function () {
            $year = now()->year;

            $lastInspection = Inspection::whereYear('created_at', $year)
                ->where('request_no', 'like', "REQ-{$year}-%")
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();

            if (!$lastInspection) {
                $nextNumber = 1;
            } else {
                $lastNumber = (int) substr($lastInspection->request_no, -4);
                $nextNumber = $lastNumber + 1;
            }

            return sprintf("REQ-%d-%04d", $year, $nextNumber);
        });
    }

    public function updateStatusApproved($id)
    {
        return $this->repository->updateStatusApproved($id);
    }

    public function updateInspection(int $id, array $data, array $items)
    {
        return DB::transaction(function () use ($id, $data, $items) {

            $inspection = $this->repository->findById($id);

            // update header
            $this->repository->update($inspection, $data);

            // delete old items
            $this->repository->deleteItems($inspection->id);

            // insert new items
            $this->repository->createInspectionItem($inspection->id, $items);

            return $inspection->fresh([
                'inspectionItems.lot',
                'inspectionItems.allocation',
                'inspectionItems.owner',
                'inspectionItems.condition'
            ]);
        });
    }

    public function validateInspectionItems(array $items)
    {
        $lotIds = collect($items)->pluck('lot_id')->unique();
        $lots = Lot::whereIn('id', $lotIds)
            ->get(['id', 'item_id', 'qty', 'price'])
            ->keyBy('id');

        $errors = [];

        foreach ($items as $item) {
            $lot = $lots[$item['lot_id']] ?? null;

            if (!$lot) {
                $errors[] = "Lot ID {$item['lot_id']} not found.";
                continue;
            }

            if ($item['qty_required'] > $lot->qty) {
                $errors[] = "Requested quantity ({$item['qty_required']}) for Lot ID {$lot->id} exceeds available stock ({$lot->qty}).";
            }
        }

        return $errors;
    }
}
