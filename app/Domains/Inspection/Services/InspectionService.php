<?php

namespace App\Domains\Inspection\Services;

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
}
