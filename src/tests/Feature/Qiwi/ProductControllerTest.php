<?php

use App\Models\Product;
use Tests\TestCase;
use Database\Seeders\CategoriesTableSeeder;

class ProductControllerTest extends TestCase
{
    private function description() {
        $this->console("Тестирование конечных точек ProductController: ");
        $this->console("- Получить список товаров по категории: ".route('get.products.category.page', ['category' => CategoriesTableSeeder::OPTION_CATEGORY]));
        $this->console("- Получить список товаров по несуществующей категории: ".route('get.products.category.page', ['category' => '0000']));
    }

    public function testGetPageableByCategory(): void {
        $this->description();

        $this->console("\nПолучить список товаров по имени категории: ".CategoriesTableSeeder::OPTION_CATEGORY);

        $response = $this->json('GET', route('get.products.category.page', ['category' => CategoriesTableSeeder::OPTION_CATEGORY]))->assertStatus(200)->json();

        $this->assertIsArray($response);

        foreach ($response as $product) {
            $this->assertArrayHasKey(Product::NAME, $product);
            $this->assertArrayHasKey(Product::PRICE, $product);
            $this->assertArrayHasKey(Product::CODE, $product);
            $this->assertArrayNotHasKey(Product::ID, $product);
            $this->assertArrayNotHasKey(Product::DESCRIPTION, $product);
            $this->assertArrayNotHasKey(Product::CREATED_AT, $product);
            $this->assertArrayNotHasKey(Product::UPDATED_AT, $product);
        }

        $this->okMsg('Получаемый список верен! Размер списка: '.count($response));
    }

    public function testGetPageableByUnknownCategory(): void {

        $this->console("\nПолучить список товаров по несуществующей категории: 000");

        $response = $this->json('GET', route('get.products.category.page', ['category' => '000']))->assertStatus(200)->json();

        $this->assertIsArray($response);

        $this->assertCount(0, $response);

        $this->okMsg('Получаемый список верен! Размер списка: '.count($response));
    }

}
