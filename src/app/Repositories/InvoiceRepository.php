<?php

namespace App\Repositories;

use App\Models\Invoice;


/**
 * Class InvoiceRepository
 */
class InvoiceRepository
{
    private Invoice $invoice;

    /**
     * InvoiceRepository constructor.
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice) {
        $this->invoice = $invoice;
    }

    /**
     * @param string $id
     * @return Invoice
     */
    final public function getInvoiceById(string $id): Invoice {
        return $this->invoice
            ->newModelQuery()
            ->where(Invoice::USER_ID, '=', $id)
            ->getModel();
    }

    /**
     * @param string $userId
     * @param string $billId
     * @return array
     */
    final public function getUserInvoiceByBillId(string $userId, string $billId): array {
        try {
           $invoice = $this->invoice->newQuery()
                ->where(Invoice::USER_ID, '=', $userId)
                ->where(Invoice::ID, '=', $billId)
                ->firstOrFail();
           return $invoice->toArray();
        } catch (\Exception $e) {
           return [];
        }
    }

    /**
     * @param array $invoice
     * @return bool
     */
    final public function addInvoice(array $invoice): bool {
        $data = $this->invoice->newQuery()->create($invoice);
        return $data->count() > 0;
    }

    /**
     * @param array $invoice
     * @return Invoice
     */
    final public function createInvoice(array $invoice): Invoice {
        return $this->invoice->newModelQuery()->create($invoice);
    }



    /**
     * @param string $id
     * @param string $status
     * @return bool
     */
    final public function updateInvoice(string $id, string $status): bool {
       return $this->invoice->newQuery()
            ->where(Invoice::ID, '=', $id)
            ->update([Invoice::STATUS => $status]) > 0;
    }
}
