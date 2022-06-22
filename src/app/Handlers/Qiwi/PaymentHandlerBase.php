<?php

namespace App\Handlers\Qiwi;
use App\dto\PayResponse;
use App\Handlers\Qiwi\Contracts\PaymentHandlerInterface;

/**
 * Class PaymentHandlerBase
 * @package App\Handlers\Qiwi
 */
abstract class PaymentHandlerBase implements PaymentHandlerInterface
{
    public const STATUSES = ['WAITING', 'PAID', 'REJECTED', 'EXPIRED'];
    public const BILL_ID = 'billId';
    public const PURCHASE_CODES = 'purchaseCodes';
    public const USER_ID = 'userId';

    public const MSG_EMPTY_ORDER_PARAMS = 'There are no order params!';
    public const MSG_EMPTY_BOTH_ORDER_PARAMS = 'Both order params are empty!';

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

       if ((($order[self::BILL_ID] === '') && empty($order[self::PURCHASE_CODES])) || ($order[self::USER_ID] === '')) {
           return new PayResponse([], self::MSG_EMPTY_BOTH_ORDER_PARAMS);
       }

       if ($order[self::BILL_ID] !== '') {
           $invoice = $this->findLastInvoice($order[self::USER_ID], $order[self::BILL_ID]);
           if (!empty($invoice)) {
               $billStatus = $this->requestBillStatus($order[self::BILL_ID]);

               if ($billStatus === 'WAITING') {

               } else if ($billStatus === 'PAID') {

               } else {

               }
           }
       }
    }

    /**
     * Найти последний выставленный счет по коду заказа
     * @param string $userId
     * @param string $billId
     * @return array
     */
    abstract public function findLastInvoice(string $userId, string $billId): array;

    /**
     * Запросить статус покупки у платежного сервера
     * @param string $billId
     * @return string
     */
    abstract public function requestBillStatus(string $billId): string;

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
