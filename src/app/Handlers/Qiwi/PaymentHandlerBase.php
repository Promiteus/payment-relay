<?php

namespace App\Handlers\Qiwi;
use App\Repository\InvoiceRepository;

/**
 * Class PaymentHandlerBase
 * @package App\Handlers\Qiwi
 */
abstract class PaymentHandlerBase
{

    final public function handleBill($body) {
       $status = '';
    }

    /**
     * Найти последний выставленный счет по типу заказа
     * @param string $userId
     * @return array
     */
    abstract public function findLastInvoice(string $userId): array;

    abstract public function requestBillStatus(string $billId): array;

    abstract public function updateInvoice(array $invoice): bool;

    abstract public function createInvoice(array $invoice): array;
}
