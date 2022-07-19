<?php


namespace App\Handlers\Qiwi;

use App\dto\BillStatusResponse;
use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\dto\PayResponse;
use App\Handlers\PaymentHandlerBase;
use App\Models\Invoice;
use App\Services\Constants\Common;
use App\Services\Contracts\ProductInvoiceServiceInterface;
use App\Services\Qiwi\Contracts\RequestPaymentServiceInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class PaymentHandler
 * @package App\Handlers\Qiwi
 */
class PaymentHandler extends PaymentHandlerBase
{
    /**
     * @var RequestPaymentServiceInterface
     */
    private RequestPaymentServiceInterface $requestPaymentService;
    /**
     * @var ProductInvoiceServiceInterface
     */
    private ProductInvoiceServiceInterface $productInvoiceService;

    /**
     * PaymentHandler constructor.
     * @param RequestPaymentServiceInterface $requestPaymentService
     * @param ProductInvoiceServiceInterface $productInvoiceService
     * @throws \Exception
     */
    public function __construct(
        RequestPaymentServiceInterface $requestPaymentService,
        ProductInvoiceServiceInterface $productInvoiceService
    ) {
        parent::__construct(now()->addDay()->toString());
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
     * @return PayResponse
     */
    final public function getBillStatus(string $billId): PayResponse
    {
        $response = $this->requestPaymentService->getBillInfo($billId);

        if ($response->getError() !== '') {
            return new PayResponse([], $response->getError());
        }
        if (!$this->updateInvoice($billId, $response->getData())) {
            return new PayResponse([], Common::MSG_CANT_UPDATE_INVOICE_STATUS);
        }

        return new PayResponse((new BillStatusResponse())->fromBodySet($response->getData())->toArray());
    }

    /**
     * @param array $params
     * @return PayResponse
     */
    final public function createBill(array $params): PayResponse
    {
        $params[Common::BILL_ID] = Uuid::uuid4()->toString();
        return $this->requestPaymentService->createBill($params);
    }

    /**
     * @param string $billId
     * @return PayResponse
     */
    final public function cancelBill(string $billId): PayResponse
    {
        $response = $this->requestPaymentService->cancelBill($billId);
        if ($response->getError() !== '') {
            return new PayResponse([], $response->getError());
        }

        if (!$this->updateInvoice($billId, $response->getData())) {
            return new PayResponse([], Common::MSG_CANT_UPDATE_INVOICE_STATUS);
        }

        return new PayResponse(app(BillStatusResponse::class)->fromBodySet($response->getData())->toArray());
    }

    /**
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    final public function findInvoice(string $billId): array
    {
        return $this->productInvoiceService->findInvoice($billId);
    }

    /**
     * @param string $billId
     * @param array $data
     * @return bool
     */
    final public function updateInvoice(string $billId, array $data): bool
    {
        $status = Common::EMPTY_STATUS;
        if (array_key_exists(Invoice::STATUS, $data) && (is_array($data[Invoice::STATUS]))) {
            $status = $data[Invoice::STATUS][Common::VALUE];
        } else  {
            $status = $data[Invoice::STATUS];
        }
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
