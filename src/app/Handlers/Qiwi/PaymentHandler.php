<?php


namespace App\Handlers\Qiwi;

use App\dto\BillStatusResponse;
use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\dto\PayResponse;
use App\Handlers\PaymentHandlerBase;
use App\Models\Invoice;
use App\Services\Constants\Common;
use App\Services\ProductInvoiceService;
use App\Services\Qiwi\RequestPaymentService;
use Ramsey\Uuid\Uuid;

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
     * @var ProductInvoiceService
     */
    private ProductInvoiceService $productInvoiceService;

    /**
     * PaymentHandler constructor.
     * @param RequestPaymentService $requestPaymentService
     * @param ProductInvoiceService $productInvoiceService
     * @throws \Exception
     */
    public function __construct(
        RequestPaymentService $requestPaymentService,
        ProductInvoiceService $productInvoiceService
    ) {
        parent::__construct($requestPaymentService->getBill()->getBillPayment()->getLifetimeByDay(1));
        $this->requestPaymentService = $requestPaymentService;
        $this->productInvoiceService = $productInvoiceService;
    }


    /*
    * Ответ Qiwi сервера при запросе статуса счета
    {
        "siteId": "6w2u7p-00",
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
        "recipientPhoneNumber": "7924107****"
    }
     */

    /**
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    final public function requestBillStatus(string $billId): array
    {
        $response = $this->requestPaymentService->getBillInfo($billId);
        if ($response->getError() !== '') {
            throw new \Exception($response->getError());
        }
        $this->updateInvoice($billId, $response[Invoice::STATUS][Common::VALUE]);

        return (new BillStatusResponse($response[Invoice::STATUS][Common::VALUE], $billId, $response[Common::PAY_URL]))->toArray();
    }


    /**
     * @param array $params
     * @return PayResponse
     */
    final public function requestCreateBill(array $params): PayResponse
    {
        $params[Common::BILL_ID] = Uuid::uuid4()->toString();
        return $this->requestPaymentService->createBill($params);
    }

    /**
     * @param string $userId
     * @param string $billId
     * @return array
     */
    final public function findInvoice(string $userId, string $billId): array
    {
        return $this->productInvoiceService->findInvoice($userId, $billId);
    }

    /**
     * @param string $billId
     * @param string $status
     * @return bool
     */
    final public function updateInvoice(string $billId, string $status): bool
    {
        return $this->productInvoiceService->updateInvoice($billId, $status);
    }


    /*
     * Ответ Qiwi сервера при создании счета
     * {
        "siteId": "6w2u7p-00",
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
        "recipientPhoneNumber": "7924107****"
   },*/

    /**
     * @param InvoiceBody $invoice
     * @param OrderBody $order
     * @return array
     * @throws \Exception
     */
    final public function createInvoice(InvoiceBody $invoice, OrderBody $order): array
    {
        return $this->productInvoiceService->createInvoice($invoice, $order);
    }
}
