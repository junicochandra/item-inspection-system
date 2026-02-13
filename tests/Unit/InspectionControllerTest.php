<?php

namespace Tests\Unit;

use App\Domains\Inspection\Http\Controllers\InspectionController;
use App\Domains\Inspection\Services\InspectionService;
use App\Domains\Order\Services\OrderService;
use App\Domains\Sow\Models\ScopeOfWork;
use Mockery;
use Tests\TestCase;

class InspectionControllerTest extends TestCase
{
    public function testShowReturnsInspectionDetail()
    {
        $mockService = $this->createMock(InspectionService::class);
        $mockOrderService = $this->createMock(OrderService::class);

        $inspectionId = 123;

        $inspectionData = [
            'id' => $inspectionId,
            'location_id' => 1,
            'customer_id' => 5,
            'status_id' => 2
        ];

        $mockService->expects($this->once())
            ->method('getInspectionDetail')
            ->with($inspectionId)
            ->willReturn($inspectionData);

        $controller = new InspectionController($mockService, $mockOrderService);

        $response = $controller->show($inspectionId);

        $responseData = $response->getData(true);

        $this->assertEquals($inspectionData, $responseData['data']);
    }

    public function testIndexReturnsListOfInspectionsWithDefaultStatus()
    {
        $mockService = $this->createMock(InspectionService::class);
        $mockOrderService = $this->createMock(OrderService::class);

        $inspectionList = [
            ['id' => 1, 'customer_id' => 5],
            ['id' => 2, 'customer_id' => 8],
        ];

        $mockService->expects($this->once())
            ->method('listInspections')
            ->with(1)
            ->willReturn($inspectionList);

        $controller = new InspectionController($mockService, $mockOrderService);

        $request = new \Illuminate\Http\Request();

        $response = $controller->index($request);

        $responseData = $response->getData(true);

        $this->assertEquals($inspectionList, $responseData['data']);
    }

    public function testIndexReturnsListOfInspectionsWithCustomStatus()
    {
        $mockService = $this->createMock(InspectionService::class);
        $mockOrderService = $this->createMock(OrderService::class);

        $statusId = 5;
        $inspectionList = [
            ['id' => 10, 'customer_id' => 2],
        ];

        $mockService->expects($this->once())
            ->method('listInspections')
            ->with($statusId)
            ->willReturn($inspectionList);

        $controller = new InspectionController($mockService, $mockOrderService);

        $request = \Illuminate\Http\Request::create('/inspections', 'GET', ['status_id' => $statusId]);

        $response = $controller->index($request);

        $responseData = $response->getData(true);

        $this->assertEquals($inspectionList, $responseData['data']);
    }

    public function testGetIncludedReturnsScopeIncludedsSuccessfully()
    {
        $mockService = $this->createMock(InspectionService::class);
        $mockOrderService = $this->createMock(OrderService::class);

        $controller = new InspectionController($mockService, $mockOrderService);

        $scopeOfWorkId = 1;

        $scopeIncludeds = [
            ['id' => 1, 'name' => 'Item 1'],
            ['id' => 2, 'name' => 'Item 2'],
        ];

        $scopeOfWorkMock = new class () {
            public $scopeIncludeds;
        };
        $scopeOfWorkMock->scopeIncludeds = $scopeIncludeds;

        $mock = Mockery::mock('alias:' . ScopeOfWork::class);
        $mock->shouldReceive('with->find')
            ->with($scopeOfWorkId)
            ->andReturn($scopeOfWorkMock);

        $response = $controller->getIncluded($scopeOfWorkId);

        $responseData = $response->getData(true);

        $this->assertTrue($responseData['success']);
        $this->assertEquals($scopeIncludeds, $responseData['data']);
    }

    public function testGetIncludedReturns404WhenScopeOfWorkNotFound()
    {
        $mockService = $this->createMock(InspectionService::class);
        $mockOrderService = $this->createMock(OrderService::class);

        $controller = new InspectionController($mockService, $mockOrderService);

        $scopeOfWorkId = 999;

        $mock = Mockery::mock('alias:' . ScopeOfWork::class);
        $mock->shouldReceive('with->find')
            ->with($scopeOfWorkId)
            ->andReturn(null);

        $response = $controller->getIncluded($scopeOfWorkId);
        $responseData = $response->getData(true);

        $this->assertFalse($responseData['success']);
        $this->assertEquals('Scope of Work not found', $responseData['message']);
        $this->assertEquals(404, $response->getStatusCode());
    }

}
