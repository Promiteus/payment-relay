<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Services\Constants\Common;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
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


    /*Очистить все сегодншние записи*/
    private function clearTodayRecords() {
        Invoice::query()->where('created_at', 'LIKE', '%'.Carbon::now()->toDateString().'%')->delete();
        ProductInvoice::query()->where('created_at', 'LIKE', '%'.Carbon::now()->toDateString().'%')->delete();
    }

    /**
     * @param string $billId
     * @throws \Exception
     */
    private function createInvoice(string $billId): void {
        $this->output->writeln('Созданеи нового счета...');

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
            $this->output->writeln("Цена товара ".$product[Product::NAME].": ".$product[Product::PRICE]);
            return $product[Product::PRICE];
        })->sum();

        $this->output->writeln("Получено товаров: ".count($productsBody));
        $this->output->writeln("Общая стоимость товаров: ".$totalPrice);


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
        $this->output->writeln('Количество счетов: '.$invoice->count());
        $this->assertTrue($invoice->count() === 1);

        /*Записей по счету billId в таблице product_invoice должно быть три*/
        $productInvoice = ProductInvoice::query()->where(ProductInvoice::INVOICE_ID, '=', $billId)->get();
        $this->output->writeln('Количество записей в '.ProductInvoice::TABLE_NAME.': '.$productInvoice->count());
        $this->assertTrue($productInvoice->count() === 3);
    }

    /**
     * @param string $billId
     * @throws \Exception
     */
    private function testCreateInvoiceEmptyInv(string $billId): void {
        $this->expectExceptionMessage(Common::MSG_NOT_ENOUGH_PARAMS);
        $this->expectException(\Exception::class);

        $this->output->writeln('Созданеи нового счета без параметров счета...');

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
        $result = $this->productInvoiceService->createInvoice($invoice, $order);
    }



    /**
     * @param string $billId
     * @throws \Exception
     */
    private function testCreateInvoiceEmptyOrder(string $billId): void {
        $this->expectExceptionMessage(Common::MSG_NOT_ENOUGH_PARAMS);
        $this->expectException(\Exception::class);

        $this->output->writeln('Созданеи нового счета без параметров товара...');

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


        /*Создать запись в таблицах invoice и product_invoice*/
        $result = $this->productInvoiceService->createInvoice($invoice, $order);
    }



    /**
     * Создать счет
     * @throws \Exception
     */
    public function testCreateInvoice(): void {
        $this->seed();

        $this->clearTodayRecords();

        $billId = Uuid::uuid4()->toString();

        $this->createInvoice($billId);

        $this->testCreateInvoiceEmptyInv($billId);

        $this->testCreateInvoiceEmptyOrder($billId);
    }
}
