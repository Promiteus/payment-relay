<?php

use App\Models\Product;
use App\Services\Constants\Common;
use Database\Seeders\UsersTableSeeder;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;
use App\Models\Invoice;

/**
 * Class PaymentControllerTest
 */
class PaymentControllerTest extends TestCase
{
    /**
     * @var string
     */
    private string $billId;

    /**
     * PaymentControllerTest constructor.
     * @param string|null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

    }

    private function description(): void {
        $this->console("\nТестирование конечных точек: ");
        $this->console("- Создать новый счет: ".route('create.bill'));
        $this->console("- Проверить статус счета: ".route('status.bill', [Common::BILL_ID => '000']));
        $this->console("- Отмена выставленного счета: ".route('cancel.bill', [Common::BILL_ID => '000']));
        $this->console("- Уведомления QIWI о смене статуса счета: ".route('notify.bill'));
    }

    /*Тест создания счета*/
    public function testCreateBill(): void {
        $this->description();

        $this->billId = Uuid::uuid4()->toString();

        $this->console("\nСозданеи нового счета.");

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

        $order = [
            Common::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Common::PRODUCTS => $productsBody,
            Common::BILL_ID => $this->billId,
            Common::TOTAL_PRICE => $totalPrice,
            Common::COMMENT => '',
            Common::EMAIL => 'dr.romanm@yandex.ru'
        ];

        $response = $this->json('POST', route('create.bill'), $order)->assertStatus(200)->json();

       // dd($response);
        $this->billId = $response[Common::DATA][Common::BILL_ID];

        $this->assertEquals(Common::WAITING_STATUS, $response[Common::DATA][\App\Models\Invoice::STATUS]);

        $this->assertNotEmpty($response[Common::DATA][Common::PAY_URL]);
        $this->assertNotEmpty($response[Common::DATA][Common::BILL_ID]);

        if (($response[Common::DATA][\App\Models\Invoice::STATUS] === Common::WAITING_STATUS) &&
            ($response[Common::DATA][Common::PAY_URL] !== '') &&
            ($response[Common::DATA][Common::BILL_ID] !== '')
        ) {
            $this->console("Номер счета: ".$response[Common::DATA][Common::BILL_ID]);
            $this->okMsg();
        }

        /*Тест получения статуса счета*/
        $this->testGetBillInfo($this->billId);
        /*Тест отклонения счета*/
        $this->testCancelBill($this->billId);
    }

    /*Тест получения статуса счета*/
    private function testGetBillInfo(string $billId): void {
        $this->console("\nПроверить статус счета: $this->billId");
        $response = $this->json('GET', route('status.bill', [Common::BILL_ID => $billId]))->assertStatus(200)->json();

        $this->assertEquals(Common::WAITING_STATUS, $response[Common::DATA][Invoice::STATUS]);

        $this->assertNotEmpty($response[Common::DATA][Common::PAY_URL]);
        $this->assertEquals($billId, $response[Common::DATA][Common::BILL_ID]);

        if (($response[Common::DATA][\App\Models\Invoice::STATUS] === Common::WAITING_STATUS) &&
            ($response[Common::DATA][Common::PAY_URL] !== '') &&
            ($response[Common::DATA][Common::BILL_ID] !== '')
        ) {
            $this->console("Статус счета: ".$response[Common::DATA][Invoice::STATUS]);
            $this->okMsg();
        }
    }

    /*Тест отклонения счета*/
    private function testCancelBill(string $billId) {
        $this->console("\nОтменить выставленный счет: $this->billId");
        $response = $this->json('POST', route('cancel.bill', [Common::BILL_ID => $billId]))->assertStatus(200)->json();

        $this->assertEquals(Common::REJECTED_STATUS, $response[Common::DATA][Invoice::STATUS]);

        $this->assertNotEmpty($response[Common::DATA][Common::PAY_URL]);
        $this->assertEquals($billId, $response[Common::DATA][Common::BILL_ID]);

        if (($response[Common::DATA][\App\Models\Invoice::STATUS] === Common::REJECTED_STATUS) &&
            ($response[Common::DATA][Common::PAY_URL] !== '') &&
            ($response[Common::DATA][Common::BILL_ID] !== '')
        ) {
            $this->console("Статус счета: ".$response[Common::DATA][Invoice::STATUS]);
            $this->okMsg();
        }
    }


    /*Тест на изменение статуса счета счета*/
    public function testNotify(): void {
        $this->console("\nОтвет (код 200) на уведомление от QIWI о смене статуса счета.");
        $response = $this->json('POST', route('notify.bill'), [])->assertStatus(200);

        if ($response->getStatusCode() === 200) {
            $this->okMsg();
        }
    }
}
