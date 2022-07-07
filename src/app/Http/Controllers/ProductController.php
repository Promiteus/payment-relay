<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
     /**
     * @var ProductService
     */
     private ProductService $productService;

    /**
     * ProductController constructor.
     * @param ProductService $productService
     */
     public function __construct(ProductService $productService)
     {
         $this->productService = $productService;
     }

    /**
     * Постраничная выдача списка товаров
     * @param Request $request
     * @return JsonResponse
     */
     final public function getPageable(Request $request): JsonResponse {
         return response()->json($this->productService->getProductsPageable(), 200);
     }

    /**
     * Постраничная выдача списка товаров по категории
     * @param string $category
     * @return JsonResponse
     */
    final public function getPageableByCategory(string $category): JsonResponse {
        return response()->json($this->productService->getProductsPageableByCategory($category), 200);
    }
}
