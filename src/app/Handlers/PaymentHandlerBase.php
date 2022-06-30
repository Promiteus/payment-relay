<?php

namespace App\Handlers;
use App\dto\PayResponse;
use App\Handlers\Contracts\PaymentHandlerInterface;
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
     *    'products' => [
     *          ['code' => '90-444', 'count' => 1, 'name' => 'товар 1', 'price' => 100.0],
     *     ],
     *    'billId' => '3920a84-33291',
     *    'totalPrice' => 100.0,
     * ]
     *
     * Обработать заказ, выставить счет и вернуть данные для перехода на форму оплаты счета
     * @param array $order
     * @return PayResponse
     */
    final public function handleBill(array $order): PayResponse {
       if ((!$order) || (empty($order))) {
           return new PayResponse([], Common::MSG_EMPTY_ORDER_PARAMS);
       }

       if ((($order[Common::BILL_ID] === '') && empty($order[Common::PRODUCTS][Common::ITEMS])) || ($order[Common::USER_ID] === '')) {
           return new PayResponse([], Common::MSG_EMPTY_BOTH_ORDER_PARAMS);
       }

       if ($order[Common::BILL_ID] !== '') {

           try {
               /*Получить последний счет из БД для пользователя user_id*/
               $invoice = $this->findInvoice($order[Common::USER_ID], $order[Common::BILL_ID]);

               if (!empty($invoice)) {

                   /*Запросить у сервиса QIWI статус счета и вернуть
                   ['status' => 'value', 'billId' => 'billValue', 'payUrl' => 'urlValue']*/
                   $billStatus = $this->requestBillStatus($order[Common::BILL_ID]);

                   if ($billStatus[Invoice::STATUS] !== '') {
                       $updatedInvoice = $this->updateInvoice($order[Common::BILL_ID], $billStatus[Invoice::STATUS]);

                       if ($updatedInvoice) {
                           if ($billStatus[Invoice::STATUS] === Common::WAITING_STATUS) {
                               return new PayResponse($billStatus, '');
                           }

                           /*Создать полностью новый счет*/
                           return $this->createOrder($order);
                       }

                       return new PayResponse([], Common::MSG_CANT_UPDATE_INVOICE_STATUS);
                   }

                   return new PayResponse([], Common::MSG_CANT_GET_INVOICE_STATUS_FROM_SERVER);
               }

               /*Создать полностью новый счет*/
               return $this->createOrder($order);
           } catch (\Exception $e) {
               return new PayResponse([], $e->getMessage());
           }
       }
    }

    /**
     * Создать полностью новый счет
     * @param array $order
     * @return PayResponse
     */
    private function createOrder(array $order): PayResponse {
        /*Создать новый счет на сервере QIWI*/
        $payResponse = $this->requestCreateBill($order);

        if ($payResponse->getError() === '') {
            /*Создать новый счет в БД*/

            try {
                $result = $this->createInvoice($payResponse->getData(), $order);
                return new PayResponse($result);
            } catch (\Exception $e) {
                return new PayResponse([], $e->getMessage());
            }
        }

        return new PayResponse([], $payResponse->getError());
    }


    /**
     * Запросить статус покупки у платежного сервера
     * @param string $billId
     * @return array
     */
    abstract public function requestBillStatus(string $billId): array;

    /**
     * @param array $params
     * @return PayResponse
     */
    abstract public function requestCreateBill(array $params): PayResponse;

    /**
     * Найти последний выставленный счет по коду заказа
     * @param string $userId
     * @param string $billId
     * @return array
     */
    abstract public function findInvoice(string $userId, string $billId): array;


    /**
     * Обновить заказ в базе для текущего пользователя
     * @param string $billId
     * @param string $status
     * @return bool
     */
    abstract public function updateInvoice(string $billId, string $status): bool;


    /**
     * @param array $invoice
     * @param array $order
     * @return array
     */
    abstract public function createInvoice(array $invoice, array $order): array;

}