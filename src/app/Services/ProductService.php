<?php


namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Получить товары по кодам товаров
     * @param string $code
     * @return array
     */
    public function getProductsByCodes(array $code): array {
        if ($code === '') {
            return [];
        }
        return $this->productRepository->getProductsByCodes($code);
    }

    /**
     * Получить список товаров постарнично
     * @return array
     */
    public function getProductsPageable(): array {
        return $this->productRepository->getPageableProducts();
    }

    /**
     * Получить список товаров по имени категории постранично
     * @param string $category
     * @return array
     */
    public function getProductsPageableByCategory(string $category): array {
        if ($category === '') {
            return [];
        }
        return $this->productRepository->getPageableProductsByCategory($category);
    }
}
