<?php


namespace App\Http\Controllers\Qiwi;

use App\dto\OrderBody;
use App\Handlers\PaymentHandlerBase;
use App\Handlers\Qiwi\PaymentHandler;
use App\Http\Controllers\Controller;
use App\Services\Qiwi\RequestPaymentService;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PaymentController
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    private RequestPaymentService $paymentService;
    private PaymentHandlerBase $paymentHandler;

    /**
     * PaymentController constructor.
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
        return response()->json($this->paymentHandler->handleBill(OrderBody::getInstance()->fromBodySet($order))->toArray(), 200);
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
        return response()->json( $this->paymentService->getBillInfo($billId)->toArray(), 200);
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
