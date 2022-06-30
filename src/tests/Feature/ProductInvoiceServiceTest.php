<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Services\Constants\Common;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ProductInvoiceServiceTest extends TestCase
{

    /**
     * @var ProductInvoiceService
     */
    private ProductInvoiceService $productInvoiceService;


    /**
     * ProductInvoiceServiceTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->productInvoiceService = app()->make(ProductInvoiceService::class);
    }


    public function testEmptyFindInvoice()
    {
        $this->seed();

        $this->console("\nПоиск несуществующего счета ...");

        $result =  $this->productInvoiceService->findInvoice('5', '5');

        $this->console("invoices: ".count($result));
        $this->assertEquals(0, count($result));

        if (!count($result)) {
            $this->okMsg();
        }
    }


    /*Очистить все сегодншние записи*/
    private function clearTodayRecords() {
        Invoice::query()->where('created_at', 'LIKE', '%'.Carbon::now()->toDateString().'%')->delete();
        ProductInvoice::query()->where('created_at', 'LIKE', '%'.Carbon::now()->toDateString().'%')->delete();
    }


    public function testCreateInvoice(): void {
        $this->seed();

        $this->clearTodayRecords();

        $billId = Uuid::uuid4()->toString();

        $this->console("\nСозданеи нового счета...");

        $products = Product::all()->take(3);

        $productsBody = $products->map(function ($item) {
            return [
                Product::CODE => $item[Product::CODE],
                Common::COUNT => 1,
                Product::NAME => $item[Product::NAME],
                Product::PRICE => $item[Product::PRICE]
            ];
        })->toArray();


        $totalPrice = $products->map(function($product) {
            return $product[Product::PRICE];
        })->sum();


        $invoice = [
            Common::BILL_ID => $billId,
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => $totalPrice,
            ],
            Invoice::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
            Common::COMMENT => 'any comment',
        ];

        $order = [
            Common::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Common::PRODUCTS => $productsBody,
            Common::BILL_ID => $billId,
            Common::TOTAL_PRICE => 100.0,
        ];

        /*Создать запись в таблицах invoice и product_invoice*/
        $result =  $this->productInvoiceService->createInvoice($invoice, $order);

        $this->assertTrue(count($result) > 0);

        /*Счет должен быть один*/
        $invoice = Invoice::query()->where(Invoice::ID, '=', $billId)->get();
        $this->console('Количество счетов: '.$invoice->count());
        $this->assertTrue($invoice->count() === 1);

        /*Записей по счету billId в таблице product_invoice должно быть три*/
        $productInvoice = ProductInvoice::query()->where(ProductInvoice::INVOICE_ID, '=', $billId)->get();
        $this->console('Количество записей в '.ProductInvoice::TABLE_NAME.': '.$productInvoice->count());
        $this->assertTrue($productInvoice->count() === 3);

        if (($productInvoice->count() === 3) && ($invoice->count() === 1)) {
            $this->okMsg();
        }
    }

    public function testCreateInvoiceEmptyInv(): void {
        $this->seed();

        $this->clearTodayRecords();

        $billId = Uuid::uuid4()->toString();

        $this->console("\nСозданеи нового счета без параметров счета...");

        $products = Product::all()->take(3);

        $productsBody = $products->map(function ($item) {
            return [
                Product::CODE => $item[Product::CODE],
                Common::COUNT => 1,
                Product::NAME => $item[Product::NAME],
                Product::PRICE => $item[Product::PRICE]
            ];
        })->toArray();


        $totalPrice = $products->map(function($product) {
            return $product[Product::PRICE];
        })->sum();



        $invoice = [];

        $order = [
            Common::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Common::PRODUCTS => $productsBody,
            Common::BILL_ID => $billId,
            Common::TOTAL_PRICE => 100.0,
        ];


        /*Создать запись в таблицах invoice и product_invoice*/
        try {
            $this->productInvoiceService->createInvoice($invoice, $order);
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }



    public function testCreateInvoiceEmptyOrder(): void {
        $this->seed();

        $this->clearTodayRecords();

        $billId = Uuid::uuid4()->toString();


        $this->console("\nСозданеи нового счета без параметров товара...");

        $products = Product::all()->take(3);

        $productsBody = $products->map(function ($item) {
            return [
                Product::CODE => $item[Product::CODE],
                Common::COUNT => 1,
                Product::NAME => $item[Product::NAME],
                Product::PRICE => $item[Product::PRICE]
            ];
        })->toArray();


        $totalPrice = $products->map(function($product) {
            return $product[Product::PRICE];
        })->sum();



        $invoice = [
            Common::BILL_ID => $billId,
            Common::AMOUNT => [
                Common::CURRENCY => 'RUB',
                Common::VALUE => $totalPrice,
            ],
            Invoice::STATUS => [
                Common::VALUE => Common::WAITING_STATUS,
            ],
            Common::COMMENT => 'any comment',
        ];

        $order = [];


        try {
            $this->productInvoiceService->createInvoice($invoice, $order);
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }

}
