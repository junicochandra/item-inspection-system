<?php

namespace App\Domains\Inventory\Services;

use App\Domains\Master\Models\MasterData;
use App\Domains\Inventory\Repositories\LotRepository;

class LotFilterService
{
    public function __construct(
        protected LotRepository $repository
    ) {
    }

    public function getFilteredOptions(
        ?int $lotId,
        ?int $allocationId,
        ?int $ownerId,
        ?int $conditionId
    ) {
        $lots = $this->repository->getAllLots();

        $baseQuery = $this->repository
            ->baseFilter($lotId, $allocationId, $ownerId, $conditionId);

        $allocationIds = (clone $baseQuery)
            ->whereNotNull('allocation_id')
            ->distinct()
            ->pluck('allocation_id');

        $ownerIds = (clone $baseQuery)
            ->whereNotNull('owner_id')
            ->distinct()
            ->pluck('owner_id');

        $conditionIds = (clone $baseQuery)
            ->whereNotNull('condition_id')
            ->distinct()
            ->pluck('condition_id');

        return [
            'lots' => $lots,

            'allocations' => MasterData::query()
                ->whereIn('id', $allocationIds)
                ->select('id', 'name', 'type')
                ->orderBy('name')
                ->get(),

            'owners' => MasterData::query()
                ->whereIn('id', $ownerIds)
                ->select('id', 'name', 'type')
                ->orderBy('name')
                ->get(),

            'conditions' => MasterData::query()
                ->whereIn('id', $conditionIds)
                ->select('id', 'name', 'type')
                ->orderBy('name')
                ->get(),
        ];
    }
}
