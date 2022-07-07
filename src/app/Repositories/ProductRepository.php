<?php


namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;

/**
 * Class ProductRepository
 * @package App\Repository
 */
class ProductRepository
{
    /**
     * @var Product
     */
    private Product $product;
    /**
     * @var Category
     */
    private Category $category;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    /**
     * Получить товары по кодам товаров
     * @param array $codes
     * @return array
     */
    final public function getProductsByCodes(array $codes): array {
        return $this->product
            ->newQuery()
            ->whereIn(Product::CODE, $codes)
            ->get()
            ->toArray();
    }

    /**
     * Получить список товаров постарнично
     * @return array
     */
    final public function getPageableProducts(): array {
        return $this->product
            ->newQuery()
            ->paginate(20)
            ->items();
    }

    /**
     * Получить список товаров по имени категории постранично
     * @param string $name
     * @return array
     */
    final public function getPageableProductsByCategory(string $name): array {
        return $this->category
            ->newQuery()
            ->with(Product::TABLE_NAME)
            ->where(Category::NAME, '=', $name)
            ->paginate(20)
            ->items();
    }
}
