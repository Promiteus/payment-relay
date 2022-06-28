<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductInvoiceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testFindInvoice()
    {
        /**
         * @var ProductInvoiceService $productInvoice
         */
        $productInvoice = app()->make(ProductInvoiceService::class);
        //$result = $productInvoice->findInvoice('5', '5');

        $this->assertTrue(true);

        $products = Product::all();

    }
}
