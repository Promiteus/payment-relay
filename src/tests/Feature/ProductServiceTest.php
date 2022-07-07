<?php


use App\Services\ProductService;
use Database\Seeders\CategoriesTableSeeder;
use Tests\TestCase;

/**
 * Class ProductServiceTest
 */
class ProductServiceTest extends TestCase
{

    private ProductService $productService;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->productService = app()->make(ProductService::class);
    }

    public function testGetProductsByCodes()
    {

        $this->assertTrue(true);
    }

    public function testGetProductsPageable()
    {

        $this->assertTrue(true);
    }

    public function testGetProductsPageableByCategory()
    {
        $products = $this->productService->getProductsPageableByCategory(CategoriesTableSeeder::OPTION_CATEGORY);
        dd($products);
        $this->assertTrue(true);
    }
}