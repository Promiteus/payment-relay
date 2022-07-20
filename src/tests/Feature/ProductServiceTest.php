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
        $this->console("\nПолучить список товаров по списку кодов товаров...");

        $codes = Product::query()->take(3)->get(Product::CODE)->toArray();
        $codes = array_map(function ($item) {
            return $item[Product::CODE];
        }, $codes);

        $products = $this->productService->getProductsByCodes($codes);

        $this->assertIsArray($products);
        $this->assertCount(3, $products);

        foreach ($products as $product) {
            $this->assertArrayHasKey(Product::NAME, $product);
            $this->assertArrayHasKey(Product::PRICE, $product);
            $this->assertArrayHasKey(Product::CODE, $product);
            $this->assertArrayHasKey(Product::ID, $product);
            $this->assertArrayHasKey(Product::DESCRIPTION, $product);
            $this->assertArrayNotHasKey(Product::CREATED_AT, $product);
            $this->assertArrayNotHasKey(Product::UPDATED_AT, $product);
        }

        $this->okMsg('Получаемый список верен! Размер списка: '.count($products));
    }

    public function testGetProductsByCodesIfNotExists() {
        $this->console("\nПолучить список товаров по несуществующему коду/кодам...");

        $products = $this->productService->getProductsByCodes([]);

        $this->assertCount(0, $products);

        $products = $this->productService->getProductsByCodes(['']);

        $this->assertCount(0, $products);

        $products = $this->productService->getProductsByCodes(['', '']);

        $this->assertCount(0, $products);

        $this->assertIsArray($products);

        $this->okMsg('Получаемый список верен (пуст)!');
    }

    public function testGetProductsPageableByCategory()
    {
        $this->console("\nПолучить список товаров по назваию категории...");

        $products = $this->productService->getProductsPageableByCategory(CategoriesTableSeeder::OPTION_CATEGORY);

        $this->assertIsArray($products);
        foreach ($products as $product) {
            $this->assertArrayHasKey(Product::NAME, $product);
            $this->assertArrayHasKey(Product::PRICE, $product);
            $this->assertArrayHasKey(Product::CODE, $product);
            $this->assertArrayNotHasKey(Product::ID, $product);
            $this->assertArrayNotHasKey(Product::DESCRIPTION, $product);
            $this->assertArrayNotHasKey(Product::CREATED_AT, $product);
            $this->assertArrayNotHasKey(Product::UPDATED_AT, $product);
        }

        $this->okMsg('Получаемый список верен! Размер списка: '.count($products));
    }

    public function testGetProductsPageableByUnknownCategory()
    {
        $this->console("\nПолучить список товаров по пустой или неизвестной категории...");

        $products = $this->productService->getProductsPageableByCategory('');

        $this->assertIsArray($products);
        $this->assertCount(0, $products);

        $products = $this->productService->getProductsPageableByCategory('test-000');

        $this->assertIsArray($products);
        $this->assertCount(0, $products);

        $this->okMsg('Получаемый список верен (пуст)!');
    }
}
