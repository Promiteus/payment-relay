<?php


namespace App\Http\Controllers\Qiwi;

use App\dto\OrderBody;
use App\Handlers\PaymentHandlerBase;
use App\Handlers\Qiwi\PaymentHandler;
use App\Http\Controllers\Controller;
use App\Services\Qiwi\RequestPaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PaymentControllerTest
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    private RequestPaymentService $paymentService;
    private PaymentHandlerBase $paymentHandler;

    /**
     * PaymentControllerTest constructor.
     * @param RequestPaymentService $paymentService
     * @param PaymentHandler $paymentHandler
     */
    public function __construct(RequestPaymentService $paymentService, PaymentHandler $paymentHandler) {
        $this->paymentService = $paymentService;
        $this->paymentHandler = $paymentHandler;
    }

    /**
     * Выставить счет
     * @param Request $request
     * @return JsonResponse
     */
    final public function create(Request $request): JsonResponse {
        $order = $this->getJsonBody($request);
        return response()
            ->json($this->paymentHandler->handleBill(OrderBody::getInstance()->fromBodySet($order))
            ->toArray(), 200);
    }

    /**
     * Отменить выставленный счет
     * @param string $billId
     * @return JsonResponse
     */
    final public function cancel(string $billId): JsonResponse {
        $response = $this->paymentHandler->cancelBill($billId);
        if ($response->getError() !== '') {
            return response()->json($response->toArray(), 500);
        }
        return response()->json($response->toArray(), 200);
    }

    /**
     * Получить информацию/статус выставленного счета
     * @param string $billId
     * @return JsonResponse
     */
    final public function info(string $billId): JsonResponse {
        $response = $this->paymentHandler->getBillStatus($billId);
        if ($response->getError() !== '') {
            return response()->json($response->toArray(), 500);
        }
        return response()->json($response->toArray(), 200);
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
