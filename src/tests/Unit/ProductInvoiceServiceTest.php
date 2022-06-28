<?php

namespace App\Services;

use PHPUnit\Framework\TestCase;

class ProductInvoiceServiceTest extends TestCase
{


    public function testFindInvoice()
    {
        /**
         * @var ProductInvoiceService $productInvoice
         */
        $productInvoice = app()->make(ProductInvoiceService::class);
        $result = $productInvoice->findInvoice('5', '5');

        $this->assertTrue(empty($result));
    }
}
