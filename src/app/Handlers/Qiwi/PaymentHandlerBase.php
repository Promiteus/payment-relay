<?php

namespace App\Handlers\Qiwi;
use App\dto\OrderResponse;
use App\dto\PayResponse;

/**
 * Class PaymentHandlerBase
 * @package App\Handlers\Qiwi
 */
abstract class PaymentHandlerBase
{
    public const STATUSES = ['WAITING', 'PAID', 'REJECTED'];
    public const BILL_ID = 'billId';
    public const PURCHASE_CODES = 'purchaseCodes';
    public const USER_ID = 'userId';

    public const MSG_EMPTY_ORDER_PARAMS = 'There are no order params!';

    /**
     * $order = [
     *    'userId' => '1',
     *    'purchaseCodes' => ['90-011', '89-017'],
     *    'billId' => '3920a84-33291'
     * ]
     *
     * Обработать заказ и вернуть данные для перехода на форму оплаты
     * @param array $order
     * @return PayResponse
     */
    final public function handleBill(array $order): PayResponse {
       if ((!$order) || (empty($order))) {
           return new PayResponse([], self::MSG_EMPTY_ORDER_PARAMS);
       }


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
