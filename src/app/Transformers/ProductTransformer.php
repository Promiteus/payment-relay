<?php

namespace App\Transformers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class ProductTransformer
 */
class ProductTransformer
{
    private static $instance = null;

    /**
     * ProductTransformer constructor.
     */
    private function __construct() {}

    /**
     * @return ProductTransformer
     */
    public static function getInstance(): ProductTransformer {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Collection $products
     * @return array
     */
    public function transform(Collection $products) {
        return $products->map(function(Product $product) {
            $product->setHidden([
                Product::CREATED_AT,
                Product::UPDATED_AT,
                Product::DESCRIPTION,
                Product::ID,
            ]);
            return $product;
        })->toArray();
    }
}
