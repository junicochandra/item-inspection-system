<?php

namespace App\Domains\Inspection\Services;

use Illuminate\Support\Facades\DB;
use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Repositories\InspectionRepository;

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
}
