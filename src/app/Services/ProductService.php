<?php


namespace App\Services;

use App\Models\Category;
use App\Repositories\ProductRepository;
use App\Transformers\ProductTransformer;

/**
 * Class ProductService
 * @package App\Services
 */
class ProductService
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * ProductService constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Получить товары по кодам товаров
     * @param array $code
     * @return array
     */
    final public function getProductsByCodes(array $code): array {
        if ($code === '') {
            return [];
        }
        return $this->productRepository->getProductsByCodes($code);
    }

    /**
     * Получить список товаров постарнично
     * @return array
     */
    final public function getProductsPageable(): array {
        return $this->productRepository->getPageableProducts();
    }

    /**
     * Получить список товаров по имени категории постранично
     * @param string $category
     * @return array
     */
    final public function getProductsPageableByCategory(string $category): array {
        if ($category === '') {
            return [];
        }

        $result = $this->productRepository->getPageableProductsByCategory($category);

        if (!empty($result) && ($result[0] instanceof Category)) {
            //return $result[0]->products->toArray();
            return ProductTransformer::getInstance()->transform($result[0]->products);
        }

        return [];
    }
}
