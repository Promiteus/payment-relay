<?php

use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\dto\PayResponse;
use App\Jobs\RequestAndUpdateInvoiceStatus;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Services\Constants\Common;
use Database\Seeders\UsersTableSeeder;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use Illuminate\Support\Facades\Bus;
use App\Services\Contracts\ProductInvoiceServiceInterface;
use App\Services\Qiwi\Contracts\BillInterface;
use App\Services\Qiwi\Contracts\RequestPaymentServiceInterface;

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

    private const AMOUNT = 100;

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
        $this->billId = Uuid::uuid4()->toString();
    }

    public function testGetOpenedInvoices(): void {
        Bus::fake([RequestAndUpdateInvoiceStatus::class]);

        $billId = Uuid::uuid4()->toString();
        /**
         * @var ProductInvoiceServiceInterface $productInvoiceService
         */
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

        /*Созжать ложный счет*/
        Invoice::query()->create([
            Invoice::ID => $billId,
            Invoice::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Invoice::PAY_URL => 'https://...',
            Invoice::CURRENCY => 'RUB',
            Invoice::EXPIRATION_DATETIME => now()->addDay()->toString(),
            Invoice::CREATED_AT => now()->toString(),
            Invoice::UPDATED_AT => now()->toString(),
            Invoice::PRICE => 100.0,
            Invoice::COMMENT => 'test queue push',
            Invoice::STATUS => Common::WAITING_STATUS,
        ]);

        /*Проверить, что счет с $billId в базе появился*/
        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId]);

        $result = $productInvoiceService->getOpenedInvoices(UsersTableSeeder::TEST_USER_ID);

        $this->assertIsArray($result);
        foreach ($result as $item) {
            $this->assertEquals(Common::WAITING_STATUS, $item[Common::STATUS]);
        }

        Bus::assertDispatchedTimes(RequestAndUpdateInvoiceStatus::class, 1);

        /*Удалить тестовый счет*/
        Invoice::query()->where(Invoice::ID, '=', $billId)->delete();
    }

    private function getMockBillStatus(string $status, string $billId): array {
        return [
            "siteId" => "6w2u7p-00",
            "billId" => $billId,
            "amount" => [
                "currency" => "RUB",
                "value" => self::AMOUNT
            ],
            "status" => [
                "value" => $status,
                "changedDateTime" => now()->toString()
            ],
            "customer" => [
                "email" => "rom3889@yandex.ru"
            ],
            "customFields" => [
                "apiClient" => "php_sdk",
                "apiClientVersion" => "0.2.2",
            ],
            "comment" => "Text comment",
            "creationDateTime" => now()->toString(),
            "expirationDateTime" => now()->addDay()->toString(),
            "payUrl" => "https://...",
            "recipientPhoneNumber" => "7924107****",
        ];
    }


    /**
     * Заглушка для метода интерфейса RequestPaymentServiceInterface
     * @param string $billId
     * @param string $method
     * @param bool $isError
     */
    private function mockRequestPaymentServiceMethod(string $billId,  string $method, bool $isError = false): void {
        $mockRequestPaymentService = \Mockery::mock(RequestPaymentServiceInterface::class);
        $mockRequestPaymentService
            ->allows($method)
            ->with($billId)
            ->andReturn(
                !$isError ? new PayResponse($this->getMockBillStatus(Common::REJECTED_STATUS, $billId), '')
                    : new PayResponse([], 'Test QIWI error')
            );

        /*Подменить класс RequestPaymentServiceInterface заглушкой в контейнере*/
        app()->instance(RequestPaymentServiceInterface::class, $mockRequestPaymentService);
    }

    public function testGetOpenedInvoicesWithChangeInvoiceStatus(): void {

        $billId = Uuid::uuid4()->toString();
        /**
         * @var ProductInvoiceServiceInterface $productInvoiceService
         */
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

        /*Созжать ложный счет*/
        Invoice::query()->create([
            Invoice::ID => $billId,
            Invoice::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Invoice::PAY_URL => 'https://...',
            Invoice::CURRENCY => 'RUB',
            Invoice::EXPIRATION_DATETIME => now()->addDay()->toString(),
            Invoice::CREATED_AT => now()->toString(),
            Invoice::UPDATED_AT => now()->toString(),
            Invoice::PRICE => 100.0,
            Invoice::COMMENT => 'test queue push',
            Invoice::STATUS => Common::WAITING_STATUS,
        ]);

        /*Проверить, что счет с $billId в базе появился*/
        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId]);

        $result = $productInvoiceService->getOpenedInvoices(UsersTableSeeder::TEST_USER_ID);

        $this->assertIsArray($result);

        $billIds = [];
        foreach ($result as $item) {
            $this->mockRequestPaymentServiceMethod($item[Invoice::ID], 'getBillInfo');
            $this->assertEquals(Common::WAITING_STATUS, $item[Common::STATUS]);
            RequestAndUpdateInvoiceStatus::dispatch($item[Invoice::ID]);


            //$this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $item[Invoice::ID], Invoice::STATUS => Common::REJECTED_STATUS]);
            $billIds[] = $item[Invoice::ID];
        }

        /*Удалить тестовый счет*/
        Invoice::query()->whereIn(Invoice::ID, $billIds)->delete();
    }

    /**
     * @throws Exception
     */
    public function testEmptyFindInvoice(): void
    {
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);
        $this->console("\nПоиск несуществующего счета ...");

        $result = $productInvoiceService->findInvoice('5');

        $this->console("invoices: ".count($result));
        $this->assertEquals(0, count($result));

        if (!count($result)) {
            $this->okMsg();
        }
    }

    /**
     *
     */
    public function testCreateInvoiceEmptyInv(): void {
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

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

        $billService = app()->make(BillInterface::class);
        /*Создать запись в таблицах invoice и product_invoice*/
        try {
            $expDate = $billService->getBillPayment()->getLifetimeByDay(1);
            $productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), app(OrderBody::class)->fromBodySet($order));
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }


    /**
     *
     */
    public function testCreateInvoiceEmptyOrder(): void {
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

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

        $billService = app()->make(BillInterface::class);
        try {
            $expDate = $billService->getBillPayment()->getLifetimeByDay(1);
            $productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), app(OrderBody::class)->fromBodySet($order));
        } catch (\Exception $e) {
            $this->okMsg($e->getMessage());
            $this->assertTrue($e->getMessage() !== '');
        }
    }

    /**
     * @throws Exception
     */
    public function testCreateInvoice(): void {
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

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

        $billService = app()->make(BillInterface::class);
        /*Создать запись в таблицах invoice и product_invoice*/
        $expDate = $billService->getBillPayment()->getLifetimeByDay(1);
        $result = $productInvoiceService->createInvoice(app(InvoiceBody::class, ['expirationDays' => $expDate])->fromBodySet($invoice), app(OrderBody::class)->fromBodySet($order));

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

    /**
     * @param string $billId
     * @throws Exception
     */
    private function testFindInvoice(string $billId): void {
        $this->console("\nПоиск действующего счета ...");
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

        $result =  $productInvoiceService->findInvoice($billId);

        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId, Invoice::STATUS => Common::WAITING_STATUS]);

        $this->assertTrue(count($result) > 0);

        if (count($result)) {
            $this->okMsg();
        }
    }


    /**
     * @param string $billId
     */
    private function testUpdateInvoice(string $billId): void {
        $this->console("\nОбновление статуса действующего счета ...");
        $productInvoiceService = app(ProductInvoiceServiceInterface::class);

        $result =  $productInvoiceService->updateInvoice($billId, Common::REJECTED_STATUS);

        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId, Invoice::STATUS => Common::REJECTED_STATUS]);

        $this->assertTrue($result);

        /*Удалит тестовый счет*/
        Invoice::query()->where(Invoice::ID, '=', $billId)->delete();
        if ($result) {
            $this->okMsg();
        }
    }

}
