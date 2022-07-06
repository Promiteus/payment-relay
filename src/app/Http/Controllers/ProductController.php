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
     private ProductService $productRepository;

     public function __construct(ProductService $productRepository)
     {
         $this->productRepository = $productRepository;
     }

    /**
     * Постраничная выдача списка товаров
     * @param Request $request
     * @return JsonResponse
     */
     final public function getPageable(Request $request): JsonResponse {
         return response()->json($this->productRepository->getProductsPageable(), 200);
     }
}
