<?php


namespace App\Repositories;

use App\Models\ProductInvoice;

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
       return $this->productInvoice->newModelQuery()->create($productInvoice);
    }
}
