<?php

namespace App\Services\Qiwi;

use App\dto\PayResponse;
use App\Services\Constants\Common;
use App\Services\Qiwi\Contracts\BillInterface;
use Illuminate\Http\JsonResponse;
use Qiwi\Api\BillPaymentsException;

/**
 * Class RequestPaymentService
 * @package App\Services\Qiwi
 */
class RequestPaymentService
{
    /**
     * @var BillInterface
     */
    private BillInterface $bill;

    /**
     * @return BillInterface
     */
    public function getBill(): BillInterface
    {
        return $this->bill;
    }


    /**
     * RequestPaymentService constructor.
     * @param BillInterface $bill
     */
    public function __construct(BillInterface $bill) {
        $this->bill = $bill;
    }

    /**
     * @param array $body
     * @return PayResponse
     */
    final public function createBill(array $body): PayResponse  {
        try {
            $params = [
                Common::AMOUNT => $body[Common::AMOUNT],
                Common::CURRENCY => 'RUB',
                Common::COMMENT => $body[Common::COMMENT],
                Common::EXPIRATION_DATE => $this->bill->getBillPayment()->getLifetimeByDay(1),
                Common::EMAIL => $body[Common::EMAIL],
                Common::CUSTOM_FIELDS => [
                    'test' => 0
                ]
            ];

            $response = $this->bill->getBillPayment()->createBill($body[Common::BILL_ID], $params);
        } catch (BillPaymentsException | \Exception $e) {
            return new PayResponse([], $e->getMessage());
        }

        return new PayResponse($response);
    }

    /**
     * @param string $billId
     * @return PayResponse
     */
    final public function getBillInfo(string $billId): PayResponse {
        $response = [];
        try {
            if ((!$billId) || ($billId === '')) {
                throw new \Exception(Common::MSG_EMPTY_BILL_ID);
            }
            $response = $this->bill->getBillPayment()->getBillInfo($billId);
        } catch (BillPaymentsException | \Exception $e) {
            return new PayResponse([], $e->getMessage());
        }

        return new PayResponse($response);
    }

    /**
     * @param string $billId
     * @return PayResponse
     */
    final public function cancelBill(string $billId): PayResponse {
        try {
            if ((!$billId) || ($billId === '')) {
                throw new \Exception(Common::MSG_EMPTY_BILL_ID);
            }
            $response = $this->bill->cancelBIllCustom($billId);
        } catch (BillPaymentsException | \Exception $e) {
            return new PayResponse([], $e->getMessage());
        }

        return new PayResponse($response);
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
