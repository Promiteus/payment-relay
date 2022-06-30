<?php


namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController extends Controller
{
    //TODO -- просто тест
     final public function getAll(Request $request): JsonResponse {
         return response()->json(Product::all(), 200);
     }
}
