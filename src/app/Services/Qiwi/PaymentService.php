<?php

namespace App\Services\Qiwi;

use App\Services\Qiwi\Contracts\BillInterface;
use Illuminate\Http\JsonResponse;
use Qiwi\Api\BillPaymentsException;

/**
 * Class PaymentService
 * @package App\Services\Qiwi
 */
class PaymentService
{
    private BillInterface $bill;

    /**
     * PaymentService constructor.
     * @param BillInterface $bill
     */
    public function __construct(BillInterface $bill) {
        $this->bill = $bill;
    }

    /**
     * @param array $body
     * @return JsonResponse
     */
    final public function createBill(array $body): JsonResponse  {
        try {
            $params = [
                BillService::AMOUNT => $body[BillService::AMOUNT],
                BillService::CURRENCY => 'RUB',
                BillService::COMMENT => $body[BillService::COMMENT],
                BillService::EXPIRATION_DATE => $this->bill->getBillPayment()->getLifetimeByDay(1),
                BillService::EMAIL => $body[BillService::EMAIL],
                //BillService::ACCOUNT => $body[BillService::ACCOUNT] ?: '',
            ];

            //dd($body[BillService::BILL_ID]);
            $response = $this->bill->getBillPayment()->createBill($body[BillService::BILL_ID], $params);
        } catch (BillPaymentsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

       // dd($response);
        return response()->json($response, 200);
    }

    /**
     * @param string $billId
     * @return JsonResponse
     */
    final public function getBillInfo(string $billId): JsonResponse {
        try {
            if ((!$billId) || ($billId === '')) {
                throw new \Exception(BillService::MSG_EMPTY_BILL_ID);
            }
            $response = $this->bill->getBillPayment()->getBillInfo($billId);
        } catch (BillPaymentsException | \Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($response, 200);
    }

    /**
     * @param string $billId
     * @return JsonResponse
     */
    final public function cancelBill(string $billId): JsonResponse {
        try {
            if ((!$billId) || ($billId === '')) {
                throw new \Exception(BillService::MSG_EMPTY_BILL_ID);
            }
            $response = $this->bill->getBillPayment()->cancelBill($billId);
        } catch (BillPaymentsException | \Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($response, 200);
    }

    /**
     * Тело уведомляющего запроса, приходящего от сервера qiwi
    {
    "bill": {
    "siteId": "9hh4jb-00",
    "billId": "cc961e8d-d4d6-4f02-b737-2297e51fb48e",
    "amount": {
    "value": "1.00",
    "currency": "RUB"
    },
    "status": {
    "value": "PAID",
    "changedDateTime": "2021-01-18T15:25:18+03"
    },
    "customer": {
    "phone": "78710009999",
    "email": "test@example.com",
    "account": "454678"
    },
    "customFields": {
    "paySourcesFilter": "qw",
    "themeCode": "Yvan-YKaSh",
    "yourParam1": "64728940",
    "yourParam2": "order 678"
    },
    "comment": "Text comment",
    "creationDateTime": "2021-01-18T15:24:53+03",
    "expirationDateTime": "2025-12-10T09:02:00+03"
    },
    "version": "1"
    }
     */
    /**
     * @param array $body
     * @return JsonResponse
     */
    final public function billStatusNotify(array $body): JsonResponse {
        //TODO - дописать логику проверки и перезаписи статуса оплаты
        return response()->json([], 200);
    }
}
