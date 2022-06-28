<?php

namespace Tests\Unit;

use App\Handlers\Qiwi\PaymentHandler;
use App\Handlers\Qiwi\PaymentHandlerBase;
use App\Repository\InvoiceRepository;
use App\Repository\ProductInvoiceRepository;
use App\Repository\ProductRepository;
use App\Services\Qiwi\RequestPaymentService;
use PHPUnit\Framework\TestCase;

class PaymentHandlerTest extends TestCase
{


    public function testCreateInvoice()
    {
        $paymentHandler = app()->make(PaymentHandler::class);
        $this->paymentHandler->findInvoice('5', '5');

        $this->assertTrue(true, 'ok 1');



    }
}
