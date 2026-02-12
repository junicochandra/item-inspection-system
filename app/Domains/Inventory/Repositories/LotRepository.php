<?php

namespace App\Domains\Inventory\Repositories;

use App\Domains\Inventory\Models\Lot;

class LotRepository
{
    public function baseFilter(
        ?int $lotId,
        ?int $allocationId,
        ?int $ownerId,
        ?int $conditionId
    ) {
        return Lot::query()
            ->when(
                !is_null($lotId),
                fn ($q) => $q->where('id', $lotId)
            )
            ->when(
                !is_null($allocationId),
                fn ($q) => $q->where('allocation_id', $allocationId)
            )
            ->when(
                !is_null($ownerId),
                fn ($q) => $q->where('owner_id', $ownerId)
            )
            ->when(
                !is_null($conditionId),
                fn ($q) => $q->where('condition_id', $conditionId)
            );
    }

    public function getAllLots()
    {
        return Lot::select('id', 'lot_no')
            ->orderBy('lot_no')
            ->get();
    }
}
