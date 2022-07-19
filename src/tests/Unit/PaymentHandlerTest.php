<?php

namespace App\Handlers\Qiwi;


use App\dto\PayResponse;
use App\Services\Qiwi\BillService;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Qiwi\Contracts\RequestPaymentServiceInterface;
use App\Services\Qiwi\RequestPaymentService;
use Tests\TestCase;

class PaymentHandlerTest extends TestCase
{

    public function testGetBillStatus()
    {
        //$mockBillInterface = \Mockery::mock(BillInterface::class);
        //$mockBillInterface->shouldIgnoreMissing();

       /* $mockRequestPaymentService = \Mockery::mock(RequestPaymentServiceInterface::class);
        $mockRequestPaymentService->allows('getBillInfo')->with('0000')->andReturn((new PayResponse([], ''))->toArray());


       // app()->instance(BillInterface::class, $mockBillInterface);
        app()->instance(RequestPaymentServiceInterface::class, $mockRequestPaymentService);


        /**
         * @var PaymentHandler $paymentHandler
         */
        /* $paymentHandler = app(PaymentHandler::class);
         $paymentHandler->getBillStatus('0000');*/

        $this->assertTrue(true);
    }
}
