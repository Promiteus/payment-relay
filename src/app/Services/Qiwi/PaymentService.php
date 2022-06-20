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
    final public function bill(array $body): JsonResponse  {
        $params = [
            BillService::AMOUNT => $body[BillService::AMOUNT],
            BillService::CURRENCY => 'RUB',
            BillService::COMMENT => $body[BillService::COMMENT],
            BillService::EXPIRATION_DATE => now()->addDay()->toString(),
            BillService::EMAIL => $body[BillService::EMAIL],
            BillService::ACCOUNT => $body[BillService::ACCOUNT],
        ];

        try {
            $response = $this->bill->getBillPayment()->createBill($body[BillService::BILL_ID], $params);
        } catch (BillPaymentsException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->json($response, 200);
    }


}
