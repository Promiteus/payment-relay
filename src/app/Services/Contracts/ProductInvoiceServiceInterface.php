<?php

namespace App\Services\Contracts;

use App\dto\InvoiceBody;
use App\dto\OrderBody;

/**
 * Interface ProductInvoiceServiceInterface
 */
interface ProductInvoiceServiceInterface
{
    public function findInvoice(string $billId): array;
    public function getOpenedInvoices(string $userId): array;
    public function updateInvoice(string $billId, string $status): bool;
    public function createInvoice(InvoiceBody $invoice, OrderBody $order): array;
}
