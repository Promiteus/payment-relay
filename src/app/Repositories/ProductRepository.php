<?php


namespace App\Repositories;

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
        return $this->product->newQuery()->whereIn(Product::CODE, $codes)->get()->toArray();
    }

    /**
     * @return array
     */
    final public function getPageableProducts(): array {
        return $this->product->newQuery()->paginate(20)->items();
    }

    final public function getPageableProductsByCategory(string $name) {
        return [];
    }
}
