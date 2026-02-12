<?php

namespace App\Domains\Order\Repositories;

use App\Domains\Inspection\Models\Order;
use Carbon\Carbon;

class OrderRepository
{
    public function create(array $data)
    {
        $data['createrd_at'] = Carbon::today()->toDateString();
        return Order::create($data);
    }

}
