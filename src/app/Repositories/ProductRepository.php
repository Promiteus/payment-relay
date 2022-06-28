<?php


namespace App\Repository;

use App\Models\Product;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository
{
    private Product $product;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @param array $codes
     * @return array
     */
    final public function getProductsByCodes(array $codes): array {
        $this->product->newQuery()->where(Product::CODE, 'in', $codes)->get()->toArray();
    }
}
