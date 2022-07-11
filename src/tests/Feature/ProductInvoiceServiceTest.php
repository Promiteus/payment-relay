<?php

use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Services\Constants\Common;
use App\Services\ProductInvoiceService;
use App\Services\Qiwi\BillService;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * Class ProductInvoiceServiceTest
 * @package App\Services
 */
class ProductInvoiceServiceTest extends TestCase
{
    /**
     * @var string
     */
    private string $billId;

    /**
     * @var ProductInvoiceService
     */
    private ProductInvoiceService $productInvoiceService;
    private BillService $billService;


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
        $this->billService = app()->make(BillService::class, ['testKey' => 'abcd']);
        $this->billId = Uuid::uuid4()->toString();
    }



    public function testEmptyFindInvoice()
    {
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


    public function testCreateInvoiceEmptyInv(): void {
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
            Common::TOTAL_PRICE => $totalPrice,
            Common::COMMENT => '',
            Common::EMAIL => 'dr.romanm@yandex.ru'
        ];


        /*Создать запись в таблицах invoice и product_invoice*/
        try {
            $expDate = $this->billService->getBillPayment()->getLifetimeByDay(1);
            $this->productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), OrderBody::getInstance()->fromBodySet($order));
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }


    public function testCreateInvoiceEmptyOrder(): void {
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
            $expDate = $this->billService->getBillPayment()->getLifetimeByDay(1);
            $this->productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), OrderBody::getInstance()->fromBodySet($order));
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }

    public function testCreateInvoice(): void {
        $this->clearTodayRecords();

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
            Common::BILL_ID => $this->billId,
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
            Common::BILL_ID =>  $this->billId,
            Common::TOTAL_PRICE => $totalPrice,
            Common::COMMENT => '',
            Common::EMAIL => 'dr.romanm@yandex.ru'
        ];

        /*Создать запись в таблицах invoice и product_invoice*/
        $expDate = $this->billService->getBillPayment()->getLifetimeByDay(1);
        $result = $this->productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), OrderBody::getInstance()->fromBodySet($order));

        $this->assertTrue(count($result) > 0);

        /*Счет должен быть один*/
        $invoice = Invoice::query()->where(Invoice::ID, '=', $this->billId)->get();
        $this->console('Количество счетов: '.$invoice->count());
        $this->assertSame($invoice->count(), 1);

        /*Записей по счету billId в таблице product_invoice должно быть три*/
        $productInvoice = ProductInvoice::query()->where(ProductInvoice::INVOICE_ID, '=', $this->billId)->get();
        $this->console('Количество записей в '.ProductInvoice::TABLE_NAME.': '.$productInvoice->count());
        $this->assertSame($productInvoice->count(), 3);

        if (($productInvoice->count() === 3) && ($invoice->count() === 1)) {
            $this->okMsg();
        }

        /*Попытаться найти счет с указанным billId*/
        $this->testFindInvoice($this->billId);

        /*Попытаться обновить статус счета с указанным billId*/
        $this->testUpdateInvoice($this->billId);
    }

    private function testFindInvoice(string $billId): void {
        $this->console("\nПоиск действующего счета ...");

        $result =  $this->productInvoiceService->findInvoice(UsersTableSeeder::TEST_USER_ID, $billId);

        $this->assertTrue(count($result) > 0);

        if (count($result)) {
            $this->okMsg();
        }
    }


    private function testUpdateInvoice(string $billId): void {
        $this->console("\nОбновление статуса действующего счета ...");

        $result =  $this->productInvoiceService->updateInvoice($billId, Common::REJECTED_STATUS);

        $this->assertTrue($result);

        if ($result) {
            $this->okMsg();
        }
    }

}
