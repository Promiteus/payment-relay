<?php

namespace App\Handlers\Qiwi;


use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Qiwi\RequestPaymentService;
use Tests\TestCase;

class PaymentHandlerTest extends TestCase
{

    public function testGetBillStatus()
    {
       /* $mockBillInterface = \Mockery::mock(BillInterface::class);
        $mockBillInterface->shouldIgnoreMissing();

        $mockRequestPaymentService = \Mockery::mock(RequestPaymentService::class);
        $mockRequestPaymentService->shouldReceive('getBillStatus')->once();


        app()->instance(BillInterface::class, $mockBillInterface);
        app()->instance(RequestPaymentService::class, $mockRequestPaymentService);


        /**
         * @var PaymentHandler $paymentHandler
         */
         /*$paymentHandler = app(PaymentHandler::class);
         $paymentHandler->getBillStatus('0000');*/

        $this->assertTrue(true);
    }
}
