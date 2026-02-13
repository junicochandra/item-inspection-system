<?php

namespace Tests\Unit;

use App\Domains\Inspection\Models\Inspection;
use App\Domains\Inspection\Services\InspectionService;
use App\Domains\Order\Http\Controllers\OrderController;
use App\Domains\Order\Services\OrderService;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    public function testApproveCreatesOrdersAndReturnsResponse()
    {
        $mockOrderService = $this->createMock(OrderService::class);
        $mockInspectionService = $this->createMock(InspectionService::class);

        $inspection = new Inspection();
        $inspection->id = 1;

        $ordersData = [
            ['id' => 1, 'item' => 'Item A'],
            ['id' => 2, 'item' => 'Item B'],
        ];

        $mockOrderService->expects($this->once())
            ->method('createFromInspection')
            ->with($inspection)
            ->willReturn($ordersData);

        $mockInspectionService->expects($this->once())
            ->method('updateStatusApproved')
            ->with($inspection->id);

        $mockOrderService->expects($this->once())
            ->method('updateStock')
            ->with($inspection);

        $controller = new OrderController($mockOrderService, $mockInspectionService);

        $response = $controller->approve($inspection);

        $responseData = $response->getData(true);

        $this->assertEquals('Orders created successfully', $responseData['message']);
        $this->assertEquals($ordersData, $responseData['data']);
    }
}
