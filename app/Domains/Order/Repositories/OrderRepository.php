<?php

namespace App\Domains\Order\Repositories;

use App\Domains\Order\Models\Order;

class OrderRepository
{
    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function existsByInspection(int $inspectionId): bool
    {
        return Order::where('inspection_id', $inspectionId)->exists();
    }

    public function nextSequence(): int
    {
        return Order::lockForUpdate()->max('sequence_number') + 1 ?? 1;
    }
}
