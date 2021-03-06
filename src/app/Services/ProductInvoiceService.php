<?php

namespace App\Services;

use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\dto\ProductItem;
use App\Jobs\RequestAndUpdateInvoiceStatus;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Repositories\InvoiceRepository;
use App\Services\Constants\Common;
use App\Services\Contracts\ProductInvoiceServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Class ProductInvoiceService
 */
class ProductInvoiceService implements ProductInvoiceServiceInterface
{
    /**
     * @var InvoiceRepository
     */
    private InvoiceRepository $invoiceRepository;
    /**
     * @var ProductService
     */
    private ProductService $productService;

    public function __construct(
        InvoiceRepository $invoiceRepository,
        ProductService $productService
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->productService = $productService;
    }

    /**
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    final public function findInvoice(string $billId): array {
        if (!$billId || ($billId === '')) {
            throw new \Exception(sprintf(Common::MSG_NOT_ALL_PARAMETERS_FOR_METHOD, __METHOD__));
        }

        return $this->invoiceRepository->getUserInvoiceByBillId($billId);
    }

    /**
     * @param string $userId
     * @return array
     */
    final public function getOpenedInvoices(string $userId): array {
        $invoices = $this->invoiceRepository->getInvoicesByStatus(Common::WAITING_STATUS, $userId);
        /*Отправить в очередь запрос на получение статуса счета и обновления его состояния в БД*/
        foreach ($invoices as $invoice) {
             RequestAndUpdateInvoiceStatus::dispatch($invoice[Invoice::ID])->onConnection('redis');
        }
        return $invoices;
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
     * @param InvoiceBody $invoice
     * @param OrderBody $order
     * @return array
     * @throws \Exception
     */
    final public function createInvoice(InvoiceBody $invoice, OrderBody $order): array
    {
        if ($invoice->getStatus() === '') {
            throw new \Exception(Common::MSG_NOT_ENOUGH_PARAMS);
        }

        if (empty($order->getProducts())) {
            throw new \Exception(Common::MSG_EMPTY_PRODUCTS);
        }

        /*Транзакция заполнения таблиц product_invoice и invoice*/
        DB::beginTransaction();

        $codes = collect($order->getProducts())->map(function (ProductItem $item) {
            return $item->getCode();
        })->toArray();


        $productIds = $this->productService->getProductsByCodes($codes);

        if (empty($productIds)) {
            DB::rollBack();
            throw new \Exception(Common::MSG_EMPTY_PRODUCTS);
        }

        $productInvoiceData = collect($productIds)->map(function ($productId) use ($invoice) {
            return [
                ProductInvoice::ID => Uuid::uuid4()->toString(),
                ProductInvoice::INVOICE_ID => $invoice->getBillId(),
                ProductInvoice::PRODUCT_ID => $productId[Product::ID],
                ProductInvoice::UPDATED_AT => Carbon::now()->toString(),
                ProductInvoice::CREATED_AT => Carbon::now()->toString(),
            ];
        })->toArray();

        $result = $this->invoiceRepository->createInvoice([
            Invoice::ID => $invoice->getBillId(),
            Invoice::STATUS => $invoice->getStatus(),
            Invoice::USER_ID => $order->getUserId(),
            Invoice::PRICE => $order->getTotalPrice(),
            Invoice::COMMENT => $invoice->getComment(),
            Invoice::CURRENCY => $invoice->getCurrency(),
            Invoice::EXPIRATION_DATETIME => $invoice->getExpirationDays(),
            Invoice::PAY_URL => $invoice->getPayUrl(),
        ])->productInvoices()->insert($productInvoiceData);

        if (!$result) {
            DB::rollBack();
            throw new \Exception(Common::MSG_CANT_CREATE_INVOICE);
        }

        DB::commit();

        return [
            Common::PAY_URL => $invoice->getPayUrl(),
            Common::BILL_ID => $invoice->getBillId(),
            Common::STATUS =>  $invoice->getStatus(),
        ];
    }
}
