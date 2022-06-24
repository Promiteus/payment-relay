<?php


namespace App\Handlers\Qiwi;

use App\dto\PayResponse;
use App\Repository\InvoiceRepository;
use App\Services\Constants\Common;
use App\Services\Qiwi\RequestPaymentService;

/**
 * Class PaymentHandler
 * @package App\Handlers\Qiwi
 */
class PaymentHandler extends PaymentHandlerBase
{
    /**
     * @var RequestPaymentService
     */
    private RequestPaymentService $requestPaymentService;
    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;

    public function __construct(RequestPaymentService $requestPaymentService, InvoiceRepository $invoiceRepository) {
        $this->requestPaymentService = $requestPaymentService;
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * @param string $userId
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    final public function findInvoice(string $userId, string $billId): array
    {
       if (!$userId || ($userId === '') || !$billId || ($billId === '')) {
           throw new \Exception(sprintf(Common::MSG_NOT_ALL_PARAMETERS_FOR_METHOD, __METHOD__));
       }

       return $this->invoiceRepository->getUserInvoiceByBillId($userId, $billId);
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
    public function requestBillStatus(string $billId): array
    {
        // TODO: Implement requestBillStatus() method.
    }

    /**
     * @inheritDoc
     */
    public function updateInvoice(array $invoice, string $status, string $userId): bool
    {
        // TODO: Implement updateInvoice() method.
    }


    /**
     * @param array $params
     * @return PayResponse
     */
    public function requestCreateBill(array $params): PayResponse
    {
        // TODO: Implement requestCreateBill() method.
    }
}
