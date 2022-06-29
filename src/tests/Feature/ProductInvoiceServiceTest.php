<?php

namespace App\Services;

use App\Models\Invoice;
use App\Services\Constants\Common;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\TestCase;

class ProductInvoiceServiceTest extends TestCase
{
    //use RefreshDatabase;

    private ConsoleOutput $output;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->output = new ConsoleOutput();
    }

    public function testEmptyFindInvoice()
    {
        $this->seed();

        /**
         * @var ProductInvoiceService $productInvoice
         */
        $productInvoice = app()->make(ProductInvoiceService::class);
        $result = $productInvoice->findInvoice('5', '5');



        $this->output->writeln("invoices: ".count($result));
        $this->assertEquals(0, count($result));

    }

    public function testCreateInvoice() {
        $this->seed();
        /**
         * @var ProductInvoiceService $productInvoice
         */
        $productInvoice = app()->make(ProductInvoiceService::class);

        $invoice = [
            Common::BILL_ID => '34456-55443',
            Common::ACCOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => 100.0,
            ],
            Invoice::STATUS => [
                Common::VALUE => Common::EXPIRED_STATUS,
            ],
            Common::COMMENT => 'any comment',
        ];

        $order = [

        ];
        $result = $productInvoice->createInvoice($invoice, $order);

        $this->assertTrue(count($result) > 0);
    }
}
