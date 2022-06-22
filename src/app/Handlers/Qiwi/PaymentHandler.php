<?php


namespace App\Handlers\Qiwi;

/**
 * Class PaymentHandler
 * @package App\Handlers\Qiwi
 */
class PaymentHandler extends PaymentHandlerBase
{

    /**
     * @inheritDoc
     */
    public function findLastInvoice(string $userId, string $billId): array
    {
        // TODO: Implement findLastInvoice() method.
    }


    /**
     **
     *"siteId": "6w2u7p-00",
    "billId": "34456-55443",
    "amount": {
    "currency": "RUB",
    "value": "1.00"
    },
    "status": {
    "value": "EXPIRED",
    "changedDateTime": "2022-06-21T09:02:23.17+03:00"
    },
    "customer": {
    "email": "rom3889@yandex.ru"
    },
    "customFields": {
    "apiClient": "php_sdk",
    "apiClientVersion": "0.2.2"
    },
    "comment": "Text comment",
    "creationDateTime": "2022-06-20T15:24:45.574+03:00",
    "expirationDateTime": "2022-06-21T09:02:00+03:00",
    "payUrl": "https://oplata.qiwi.com/form/?invoice_uid=b70ec2a4-9004-43d2-b7bd-6ef7a64ba6db",
    "recipientPhoneNumber": "79241071905"
     */

    /**
     * @inheritDoc
     */
    public function requestBillStatus(string $billId): string
    {
        // TODO: Implement requestBillStatus() method.
    }

    /**
     * @inheritDoc
     */
    public function updateInvoice(array $invoice, string $userId): bool
    {
        // TODO: Implement updateInvoice() method.
    }

    /**
     * @inheritDoc
     */
    public function createInvoice(array $invoice, string $userId): array
    {
        // TODO: Implement createInvoice() method.
    }
}
