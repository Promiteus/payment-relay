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
    /**
     * PaymentService constructor.
     * @param BillInterface $bill
     */
    public function __construct(private BillInterface $bill) {}

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
                BillService::EXPIRATION_DATE => now()->addDay()->toString(),
                BillService::EMAIL => $body[BillService::EMAIL],
                BillService::ACCOUNT => $body[BillService::ACCOUNT],
            ];

            $response = $this->bill->getBillPayment()->createBill($body[BillService::BILL_ID], $params);
        } catch (BillPaymentsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

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
     * @param array $body
     * @return JsonResponse
     */
    final public function billStatusNotify(array $body): JsonResponse {
        return response()->json([], 200);
    }
}
