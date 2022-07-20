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
     * @param string $userId
     * @return JsonResponse
     */
    final public function getInvoicesByStatus(string $userId): JsonResponse {
        $result = $this->productInvoiceService->getOpenedInvoices($userId);

        return response()->json($result, 200);
    }
}
