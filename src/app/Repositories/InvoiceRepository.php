<?php

namespace App\Repository;

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
     * @return array
     */
    final public function getInvoiceById(string $id): array {
        return $this->invoice
            ->newQuery()
            ->where(Invoice::USER_ID, '=', $id)
            ->get()
            ->toArray();
    }

    /**
     * @param string $userId
     * @return array
     */
    final public function getLastUserInvoice(string $userId, string $status): array {
        try {
           $invoice = $this->invoice->newQuery()
                ->where(Invoice::USER_ID, '=', $userId)
                ->where(Invoice::STATUS, '=', $status)
                ->orderBy(Invoice::CREATED_AT, 'desc')
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