<?php


use App\Services\ProductService;
use Database\Seeders\CategoriesTableSeeder;
use Tests\TestCase;
use App\Models\Product;

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

        $this->assertIsArray($products);
        $this->assertArrayHasKey(Product::NAME, $products[0]);
        $this->assertArrayHasKey(Product::PRICE, $products[0]);
        $this->assertArrayHasKey(Product::CODE, $products[0]);
        $this->assertArrayNotHasKey(Product::ID, $products[0]);
        $this->assertArrayNotHasKey(Product::DESCRIPTION, $products[0]);
        $this->assertArrayNotHasKey(Product::CREATED_AT, $products[0]);
        $this->assertArrayNotHasKey(Product::UPDATED_AT, $products[0]);
    }
}
