<?php


namespace App\Repository;

use App\Models\ProductInvoice;
use Carbon\Carbon;

/**
 * Class ProductInvoiceRepository
 * @package App\Repository
 */
class ProductInvoiceRepository
{
    private ProductInvoice $productInvoice;

    /**
     * ProductInvoiceRepository constructor.
     * @param ProductInvoice $productInvoice
     */
    public function __construct(ProductInvoice $productInvoice) {
        $this->productInvoice = $productInvoice;
    }

    /**
     * @param array $productInvoice
     * @return ProductInvoice
     */
    final public function add(array $productInvoice): ProductInvoice {
        $this->productInvoice->newModelQuery()->create($productInvoice);
    }
}
