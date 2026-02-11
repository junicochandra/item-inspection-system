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
        $baseQuery = $this->repository
            ->baseFilter($lotId, $allocationId, $ownerId, $conditionId);

        $lots = (clone $baseQuery)
            ->select('id', 'lot_no')
            ->distinct()
            ->get();

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
            'allocations' => MasterData::whereIn('id', $allocationIds)
                ->select('id', 'name', 'type')
                ->get(),

            'owners' => MasterData::whereIn('id', $ownerIds)
                ->select('id', 'name', 'type')
                ->get(),

            'conditions' => MasterData::whereIn('id', $conditionIds)
                ->select('id', 'name', 'type')
                ->get(),
        ];
    }
}
