<?php

namespace App\Handlers\Qiwi;
use App\dto\PayResponse;
use App\Repository\InvoiceRepository;

/**
 * Class PaymentHandlerBase
 * @package App\Handlers\Qiwi
 */
abstract class PaymentHandlerBase
{
    public const STATUSES = ['WAITING', 'PAID', 'REJECTED'];

    /**
     * $body = [
     *    'userId' => '1',
     *    'purchaseCodes' => ['90-011', '89-017'],
     * ]
     *
     * Обработать заказ и вернуть данные для перехода на форму оплаты
     * @param $body
     * @return PayResponse
     */
    final public function handleBill($body): PayResponse {
       // $this->findLastInvoice()

    }

    /**
     * Найти последний выставленный счет по коду заказа
     * @param string $userId
     * @param string $purchaseCode
     * @return array
     */
    abstract public function findLastInvoice(string $userId, string $purchaseCode): array;

    /**
     * Запросить статус покупки у платежного сервера
     * @param string $billId
     * @return array
     */
    abstract public function requestBillStatus(string $billId): array;

    /**
     * Обновить заказ в базе для текущего пользователя
     * @param array $invoice
     * @param string $userId
     * @return bool
     */
    abstract public function updateInvoice(array $invoice, string $userId): bool;

    /**
     * Создать новый заказ в базе для текущего пользователя
     * @param array $invoice
     * @param string $userId
     * @return array
     */
    abstract public function createInvoice(array $invoice, string $userId): array;
}
