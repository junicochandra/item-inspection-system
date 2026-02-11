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
                $lotId,
                fn ($q) =>
                $q->where('id', $lotId)
            )
            ->when(
                $allocationId,
                fn ($q) =>
                $q->where('allocation_id', $allocationId)
            )
            ->when(
                $ownerId,
                fn ($q) =>
                $q->where('owner_id', $ownerId)
            )
            ->when(
                $conditionId,
                fn ($q) =>
                $q->where('condition_id', $conditionId)
            );
    }
}
