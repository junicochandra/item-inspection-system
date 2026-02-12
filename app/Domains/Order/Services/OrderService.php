<?php

namespace App\Domains\Order\Services;

use App\Domains\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $repository
    ) {
        //
    }

    public function create(array $data)
    {
        dd($data);
        return $this->repository->create($data);
    }
}
