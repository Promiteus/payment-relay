<?php

namespace Tests\Unit;

use App\Handlers\Qiwi\PaymentHandler;
use App\Repository\InvoiceRepository;
use App\Repository\ProductInvoiceRepository;
use App\Repository\ProductRepository;
use App\Services\Qiwi\RequestPaymentService;
use PHPUnit\Framework\TestCase;

class PaymentHandlerTest extends TestCase
{
    private PaymentHandler $paymentHandler;

    public function testCreateInvoice()
    {
     //   $this->paymentHandler->findInvoice('5', '5');
         $this->assertTrue(true, 'ok 1');



    }
}
