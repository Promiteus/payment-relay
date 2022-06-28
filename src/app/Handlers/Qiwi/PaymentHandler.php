<?php


namespace App\Handlers\Qiwi;

use App\dto\PayResponse;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Repository\InvoiceRepository;
use App\Repository\ProductInvoiceRepository;
use App\Repository\ProductRepository;
use App\Services\Constants\Common;
use App\Services\Qiwi\RequestPaymentService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;
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
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;
    /**
     * @var ProductInvoiceRepository
     */
    private ProductInvoiceRepository $productInvoiceRepository;
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(
        RequestPaymentService $requestPaymentService,
        InvoiceRepository $invoiceRepository,
        ProductInvoiceRepository $productInvoiceRepository,
        ProductInvoice $productInvoice
    ) {
        $this->requestPaymentService = $requestPaymentService;
        $this->invoiceRepository = $invoiceRepository;
        $this->productRepository = $productInvoice;
        $this->productInvoiceRepository = $productInvoiceRepository;
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
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    #[ArrayShape([Invoice::STATUS => "mixed", Common::BILL_ID => "string", Common::PAY_URL => "mixed"])]
    final public function requestBillStatus(string $billId): array
    {
        $response = $this->requestPaymentService->getBillInfo($billId);
        if ($response->getError() !== '') {
            throw new \Exception($response->getError());
        }
        return [
            Invoice::STATUS => $response[Invoice::STATUS]->value,
            Common::BILL_ID => $billId,
            Common::PAY_URL => $response[Common::PAY_URL],
        ];
    }

    /**
     * @param string $billId
     * @param string $status
     * @return bool
     */
    final public function updateInvoice(string $billId, string $status): bool
    {
        if (($billId === '') || ($status === '')) {
            return false;
        }
        return $this->invoiceRepository->updateInvoice($billId, $status);
    }


    /**
     * @param array $params
     * @return PayResponse
     */
    final public function requestCreateBill(array $params): PayResponse
    {
        $params[Common::BILL_ID] = Uuid::uuid4();
        return $this->requestPaymentService->createBill($params);
    }

    /**
     * @param array $invoice
     * @param array $order
     * @return array
     * @throws \Exception
     */
    final public function createInvoice(array $invoice, array $order): array
    {
        $inv = [
            Invoice::ID => $invoice[Common::BILL_ID],
            Invoice::STATUS => $invoice[Invoice::STATUS]->value,
            Invoice::USER_ID => $order[Common::USER_ID],
            Invoice::PRICE => 0,
            Invoice::COMMENT => $invoice[Invoice::COMMENT],
            Invoice::CURRENCY => $invoice[Invoice::CURRENCY],
        ];

        if (empty($order[Common::PRODUCTS])) {
            throw new \Exception(Common::MSG_EMPTY_PRODUCTS);
        }

        /*Транзакция заполнения таблиц product_invoice и invoice*/
        DB::beginTransaction();

        $productIds = $this->productRepository->getProductsByCodes(collect($order[Common::PRODUCTS])->map(function ($item) {
            return $item[Product::CODE];
        })->toArray());

        if (empty($productIds)) {
            DB::rollBack();
            throw new \Exception(Common::MSG_PRODUCTS_WITH_SUCH_CODES_NOT_FOUND);
        }

        $productInvoiceData = collect($productIds)->map(function ($productId) use ($invoice) {
            return [
                ProductInvoice::INVOICE_ID => $invoice[Common::BILL_ID],
                ProductInvoice::PRODUCT_ID => $productId,
                ProductInvoice::UPDATED_AT => Carbon::now(),
                ProductInvoice::CREATED_AT => Carbon::now(),
            ];
        })->toArray();

        $result = $this->productInvoiceRepository
            ->add($productInvoiceData)
            ->invoice()
            ->newQuery()
            ->insert($inv);

        if (!$result) {
            DB::rollBack();
            throw new \Exception(Common::MSG_CANT_CREATE_INVOICE);
        }

        DB::commit();

        return $inv;
    }
}
