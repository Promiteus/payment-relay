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

    public function getProductsPageable(): array {
        return $this->productRepository->getPageableProducts();
    }
}
