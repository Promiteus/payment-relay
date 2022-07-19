<?php

namespace App\Handlers\Qiwi;

use App\dto\PayResponse;
use App\Models\Invoice;
use App\Services\Constants\Common;
use App\Services\Qiwi\Contracts\RequestPaymentServiceInterface;
use Database\Seeders\UsersTableSeeder;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class PaymentHandlerTest extends TestCase
{
    private const AMOUNT = 100;

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
    private function mockRequestPaymentServiceMethod(string $billId, string $method, bool $isError = false): void {
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

    /**Получить статус счета от QIWI сервера и обновить статус счета в таблице invoices*/
    public function testGetBillStatus()
    {
        $this->console("\nПолучить статус счета от QIWI сервера и обновить статус счета в таблице invoices");
        $billId = Uuid::uuid4()->toString();

        $this->mockRequestPaymentServiceMethod($billId, 'getBillInfo');

        /**
         * @var PaymentHandler $paymentHandler
         */
         $paymentHandler = app(PaymentHandler::class);

         /*Созжать ложный счет*/
         Invoice::query()->create([
             Invoice::ID => $billId,
             Invoice::USER_ID => UsersTableSeeder::TEST_USER_ID,
             Invoice::PAY_URL => 'https://...',
             Invoice::CURRENCY => 'RUB',
             Invoice::EXPIRATION_DATETIME => now()->addDay()->toString(),
             Invoice::CREATED_AT => now()->toString(),
             Invoice::UPDATED_AT => now()->toString(),
             Invoice::PRICE => self::AMOUNT,
             Invoice::COMMENT => 'test update status',
             Invoice::STATUS => Common::WAITING_STATUS,
         ]);
         /*Проверить, что счет с $billId в базе появился*/
         $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId]);

         $response = $paymentHandler->getBillStatus($billId);

         /*Проверить, что счет с $billId в базе имеет указанный статус*/
         $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId, Invoice::STATUS => Common::REJECTED_STATUS]);

         /*Удалит тестовый счет*/
         Invoice::query()->where(Invoice::ID, '=', $billId)->delete();

         self::assertNotEmpty($response->getData()[Common::BILL_ID]);
         self::assertNotEmpty($response->getData()[Common::PAY_URL]);
         self::assertNotEmpty($response->getData()[Common::STATUS]);

         $this->okMsg();
    }

    /**Получить ошибку от QIWI сервера, статус в таблице invoices не обновится*/
    public function testGetBillStatusWithQiwiError()
    {
        $this->console("\nПолучить ошибку от QIWI сервера, статус в таблице invoices не обновится");
        $billId = Uuid::uuid4()->toString();

        $this->mockRequestPaymentServiceMethod($billId, 'getBillInfo', true);
        /**
         * @var PaymentHandler $paymentHandler
         */
        $paymentHandler = app(PaymentHandler::class);

        /*Созжать ложный счет*/
        Invoice::query()->create([
            Invoice::ID => $billId,
            Invoice::USER_ID => UsersTableSeeder::TEST_USER_ID,
            Invoice::PAY_URL => 'https://...',
            Invoice::CURRENCY => 'RUB',
            Invoice::EXPIRATION_DATETIME => now()->addDay()->toString(),
            Invoice::CREATED_AT => now()->toString(),
            Invoice::UPDATED_AT => now()->toString(),
            Invoice::PRICE => self::AMOUNT,
            Invoice::COMMENT => 'test update status',
            Invoice::STATUS => Common::WAITING_STATUS,
        ]);
        /*Проверить, что счет с $billId в базе появился*/
        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId]);

        $response = $paymentHandler->getBillStatus($billId);
        /*Проверить, что счет с $billId в базе имеет указанный статус*/
        $this->assertDatabaseHas(Invoice::TABLE_NAME, [Invoice::ID => $billId, Invoice::STATUS => Common::WAITING_STATUS]);

        /*Удалит тестовый счет*/
        Invoice::query()->where(Invoice::ID, '=', $billId)->delete();

        self::assertEmpty($response->getData());
        self::assertNotEmpty($response->getError());

        $this->okMsg();
    }
}
