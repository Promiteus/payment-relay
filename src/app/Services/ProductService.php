<?php


namespace App\Services;

use App\Repositories\ProductRepository;

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
        return $this->productRepository->getPageableProductsByCategory($category);
    }
}
