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

    public function listInspections()
    {
        return $this->repository->getList();
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
}
