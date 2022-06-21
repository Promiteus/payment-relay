<?php


namespace App\Http\Controllers\Qiwi;

use App\Http\Controllers\Controller;
use App\Services\Qiwi\RequestPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    private RequestPaymentService $paymentService;
    /**
     * PaymentController constructor.
     * @param RequestPaymentService $paymentService
     */
    public function __construct(RequestPaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

    /**
     * Выставить счет
     * @param Request $request
     * @return JsonResponse
     */
    final public function create(Request $request): JsonResponse {
        $body = $this->getJsonBody($request);
        return $this->paymentService->createBill($body);
    }

    /**
     * Отменить выставленный счет
     * @param string $billId
     * @return JsonResponse
     */
    final public function cancel(string $billId): JsonResponse {
        return $this->paymentService->cancelBill($billId);
    }

    /**
     * Получить информацию/статус выставленного счета
     * @param string $billId
     * @return JsonResponse
     */
    final public function info(string $billId): JsonResponse {
        return $this->paymentService->getBillInfo($billId);
    }

    /**
     * Получить уведомление от сервера QIWI о статусе оплаты
     * @param Request $request
     * @return JsonResponse
     */
    final public function notify(Request $request): JsonResponse {
        $body = $this->getJsonBody($request);
        return $this->paymentService->billStatusNotify($body);
    }
}
