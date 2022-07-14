<?php

namespace App\Http\Controllers;

use App\Services\ProductInvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class InvoiceController
 * @package App\Http\Controllers
 */
class InvoiceController extends Controller
{
    /**
     * @var ProductInvoiceService
     */
    private ProductInvoiceService $productInvoiceService;

    /**
     * InvoiceController constructor.
     * @param ProductInvoiceService $productInvoiceService
     */
    public function __construct(ProductInvoiceService $productInvoiceService)
    {
        $this->productInvoiceService = $productInvoiceService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getInvoicesByStatus(Request $request): JsonResponse {
        $userId = 300; //TODO - userId получать из авторизации. 300 - тестовый userId
        $result = $this->productInvoiceService->getOpenedInvoices($userId);

        dd($result);
        return response()->json($result, 200);
    }
}
