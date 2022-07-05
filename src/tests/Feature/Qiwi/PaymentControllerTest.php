<?php

use App\Models\Product;
use App\Services\Constants\Common;
use Database\Seeders\UsersTableSeeder;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

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

    /*Тест создания счета*/
    public function testCreateBill() {
        $this->seed();

        $this->billId = Uuid::uuid4()->toString();

        $this->console("\nСозданеи нового счета по rest api...");

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

        $response = $this->json('POST', route('create.bill'), $order)->json();

        $this->billId = $response[Common::DATA][Common::BILL_ID];

        self::assertTrue(true);


    }

    /*Тест получения статуса счета*/
    public function testGetBillInfo() {
      /*  $url = route('create.bill');
        $this->console($url);
        $this->assertTrue($url !== '');*/
    }

    /*Тест отклонения счета*/
    public function testCancelBill() {

    }

    /*Тест на изменение статуса счета счета*/
    public function testNotify() {

    }
}
