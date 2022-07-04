<?php

namespace App\Services;

use App\dto\InvoiceBody;
use App\dto\OrderBody;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Repositories\InvoiceRepository;
use App\Repositories\ProductInvoiceRepository;
use App\Repositories\ProductRepository;
use App\Services\Constants\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

/**
 * Class ProductInvoiceService
 */
class ProductInvoiceService
{
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
        InvoiceRepository $invoiceRepository,
        ProductInvoiceRepository $productInvoiceRepository,
        ProductRepository $productRepository
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->productRepository = $productRepository;
        $this->productInvoiceRepository = $productInvoiceRepository;
    }

    /**
     * @param string $userId
     * @param string $billId
     * @return array
     * @throws \Exception
     */
    final public function findInvoice(string $userId, string $billId): array {
        if (!$userId || ($userId === '') || !$billId || ($billId === '')) {
            throw new \Exception(sprintf(Common::MSG_NOT_ALL_PARAMETERS_FOR_METHOD, __METHOD__));
        }

        return $this->invoiceRepository->getUserInvoiceByBillId($userId, $billId);
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
        if (!$invoice) {
            throw new \Exception(Common::MSG_NOT_ENOUGH_PARAMS);
        }

        if (empty($order->getProducts())) {
            throw new \Exception(Common::MSG_EMPTY_PRODUCTS);
        }

        /*Транзакция заполнения таблиц product_invoice и invoice*/
        DB::beginTransaction();

        $inv = [
            Invoice::ID => $invoice->getBillId(),
            Invoice::STATUS => $invoice->getStatus(),
            Invoice::USER_ID => $order->getUserId(),
            Invoice::PRICE => $invoice->getAmount(),
            Invoice::COMMENT => $invoice->getComment(),
            Invoice::CURRENCY => $invoice->getCurrency(),
        ];

        $products = collect($order->getProducts())->map(function ($item) {
            return $item->getCode();
        })->toArray();


        $productIds = $this->productRepository->getProductsByCodes($products);

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

        $result = $this->invoiceRepository->createInvoice($inv)->productInvoices()->insert($productInvoiceData);

        if (!$result) {
            DB::rollBack();
            throw new \Exception(Common::MSG_CANT_CREATE_INVOICE);
        }

        DB::commit();

        return $inv;
    }
}
