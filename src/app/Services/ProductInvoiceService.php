<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;
use App\Repository\InvoiceRepository;
use App\Repository\ProductInvoiceRepository;
use App\Repository\ProductRepository;
use App\Services\Constants\Common;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
     * @throws Exception
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
