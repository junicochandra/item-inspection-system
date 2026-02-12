<?php

namespace App\Domains\Order\Services;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inventory\Repositories\LotRepository;
use App\Domains\Order\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        private OrderRepository $repository,
        private LotRepository $lotRepository
    ) {
        //
    }

    public function createFromInspection(Inspection $inspection)
    {
        DB::beginTransaction();

        try {

            if ($this->repository->existsByInspection($inspection->id)) {
                throw new \Exception('Order already created for this inspection');
            }

            $orders = [];

            foreach ($inspection->inspectionItems as $item) {

                $sequence = $this->repository->nextSequence();

                $orders[] = $this->repository->create([
                    'sequence_number'    => $sequence,
                    'code'          => 'ORD-' . str_pad($sequence, 3, '0', STR_PAD_LEFT),
                    'type'          => 'SERV-' . str_pad($sequence, 3, '0', STR_PAD_LEFT) . ' Inspection',
                    'inspection_id' => $inspection->id,
                    'inspection_item_id' => $item->id,
                    'total_amount'  => $item->subtotal,
                ]);
            }

            DB::commit();

            return $orders;

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateStock(Inspection $inspection): void
    {
        $inspection->load('inspectionItems');
        foreach ($inspection->inspectionItems as $item) {

            if (!$item->lot_id) {
                continue;
            }

            $lot = $this->lotRepository->findForUpdate($item->lot_id);

            $newQty = $lot->qty - $item->qty_required;

            if ($newQty < 0) {
                throw new \Exception("Insufficient stock for Lot ID {$lot->id}");
            }

            $this->lotRepository->updateQty($lot, $newQty);
        }
    }
}
