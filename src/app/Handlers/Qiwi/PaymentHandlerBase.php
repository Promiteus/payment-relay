<?php

namespace App\Handlers\Qiwi;
use App\dto\PayResponse;
use App\Handlers\Qiwi\Contracts\PaymentHandlerInterface;
use App\Models\Invoice;
use App\Services\Constants\Common;

/**
 * Class PaymentHandlerBase
 * @package App\Handlers\Qiwi
 */
abstract class PaymentHandlerBase implements PaymentHandlerInterface
{
    /**
     * $order = [
     *    'userId' => '1',
     *    'purchaseCodes' => ['90-011', '89-017'],
     *    'billId' => '3920a84-33291',
     *    'totalPrice' => 100.0,
     * ]
     *
     * Обработать заказ и вернуть данные для перехода на форму оплаты
     * @param array $order
     * @return PayResponse
     */
    public function handleBill(array $order): PayResponse {
       if ((!$order) || (empty($order))) {
           return new PayResponse([], Common::MSG_EMPTY_ORDER_PARAMS);
       }

       if ((($order[Common::BILL_ID] === '') && empty($order[Common::PURCHASE_CODES])) || ($order[Common::USER_ID] === '')) {
           return new PayResponse([], Common::MSG_EMPTY_BOTH_ORDER_PARAMS);
       }

       if ($order[Common::BILL_ID] !== '') {

           /*Получить последний счет из БД для пользователя user_id*/
           $invoice = $this->findLastInvoice($order[Common::USER_ID], $order[Common::BILL_ID]);

           if (!empty($invoice)) {

               /*Запросить у сервиса QIWI статус счета и вернуть ['status' => 'value', 'billId' => 'billValue', 'payUrl' => 'urlValue']*/
               $billStatus = $this->requestBillStatus($order[Common::BILL_ID]);

               if ($billStatus[Invoice::STATUS] === Common::WAITING_STATUS) {

                   $updateResult = $this->updateInvoice($billStatus, $order[Common::USER_ID]);

                   return new PayResponse($billStatus, '');

               }

               if (($billStatus[Invoice::STATUS] === Common::PAID_STATUS) ||
                   ($billStatus[Invoice::STATUS] === Common::EXPIRED_STATUS) ||
                   ($billStatus[Invoice::STATUS] === Common::REJECTED_STATUS)) {

                   /*Создать новый счет на сервере QIWI*/
                   $payResponse = $this->requestCreateBill($order);

                   if ($payResponse->getError() === '') {
                       /*Создать новый счет*/
                       return $this->createInvoice($payResponse->getData(), $order[Common::USER_ID]);
                   }

                   return new PayResponse([], $payResponse->getError());

               }

           } else {

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
     * @return array
     */
    abstract public function requestBillStatus(string $billId): array;

    /**
     * Обновить заказ в базе для текущего пользователя
     * @param array $invoice
     * @param string $userId
     * @return array
     */
    abstract public function updateInvoice(array $invoice, string $userId): array;

    /**
     * Создать новый заказ в базе для текущего пользователя
     * @param array $invoice
     * @param string $userId
     * @return PayResponse
     */
    abstract public function createInvoice(array $invoice, string $userId): PayResponse;

    /**
     * @param array $params
     * @return PayResponse
     */
    abstract public function requestCreateBill(array $params): PayResponse;
}
