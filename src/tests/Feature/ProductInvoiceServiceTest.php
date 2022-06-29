<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Product;
use App\Services\Constants\Common;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tests\TestCase;

class ProductInvoiceServiceTest extends TestCase
{
    //use RefreshDatabase;
    /**
     * @var ConsoleOutput
     */
    private ConsoleOutput $output;
    /**
     * @var ProductInvoiceService
     */
    private ProductInvoiceService $productInvoiceService;


    /**
     * ProductInvoiceServiceTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->output = new ConsoleOutput();
        $this->productInvoiceService = app()->make(ProductInvoiceService::class);
    }



    public function testEmptyFindInvoice()
    {
        $this->seed();

        $result =  $this->productInvoiceService->findInvoice('5', '5');

        $this->output->writeln("invoices: ".count($result));
        $this->assertEquals(0, count($result));
    }


    public function testCreateInvoice() {
        $this->seed();

        $products = Product::all()->take(3);

        $productsBody = $products->map(function ($item) {
            return [
                Product::CODE => $item[Product::CODE],
                Common::COUNT => 1,
                Product::NAME => $item[Product::NAME],
                Product::PRICE => $item[Product::PRICE]
            ];
        })->toArray();

        $totalPrice = 1000;/*$products->reduce(function ($item) {
            return ($item[Common::COUNT] * $item[Product::PRICE]);
        });*/

        $this->output->writeln("Получено товаров: ".count($productsBody));
        $this->output->writeln("Общая стоимость товаров: ".$totalPrice);

        $invoice = [
            Common::BILL_ID => '34456-55443',
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => $totalPrice,
            ],
            Invoice::STATUS => [
                Common::VALUE => Common::EXPIRED_STATUS,
            ],
            Common::COMMENT => 'any comment',
        ];

        $order = [
            Common::USER_ID => '200',
            Common::PRODUCTS => $productsBody,
            Common::BILL_ID => '34456-55443',
            Common::TOTAL_PRICE => 100.0,
        ];

        /*Создать запись в таблицах invoice и product_invoice*/
        $result =  $this->productInvoiceService->createInvoice($invoice, $order);

        $this->assertTrue(count($result) > 0);
    }
}
