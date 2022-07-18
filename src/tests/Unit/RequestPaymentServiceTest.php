<?php

namespace App\Services\Qiwi;

use App\Services\Qiwi\Contracts\BillInterface;
use PHPUnit\Framework\TestCase;

class RequestPaymentServiceTest extends TestCase
{

    public function testGetBillInfo()
    {
        $mockBillInterface = \Mockery::mock(BillService::class);
        $mockBillInterface->shouldIgnoreMissing();

        app()->instance(BillInterface::class, $mockBillInterface);

        /**
         * @var RequestPaymentService $requestPaymentService
         */
        $requestPaymentService = app(RequestPaymentService::class);

        $requestPaymentService->getBillInfo('000');

        self::assertTrue(true);
    }
}
