<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ProductsTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table(Product::TABLE_NAME)->delete();

       $category = DB::table(Category::TABLE_NAME)
           ->where(Category::NAME, '=', CategoriesTableSeeder::OPTION_CATEGORY)
           ->get(Category::ID)
           ->toArray();


       DB::table(Product::TABLE_NAME)->insert([
           [
               Product::ID => Uuid::uuid4()->toString(),
               Product::PRICE => 100.0,
               Product::CODE => '10-00',
               Product::NAME => 'Product 1',
               Product::CATEGORY_ID => $category[0]->id,
               Product::CREATED_AT => Carbon::now()->toString(),
               Product::UPDATED_AT => Carbon::now()->toString()
           ],
           [
               Product::ID => Uuid::uuid4()->toString(),
               Product::PRICE => 300.0,
               Product::CODE => '10-01',
               Product::NAME => 'Product 2',
               Product::CATEGORY_ID => $category[0]->id,
               Product::CREATED_AT => Carbon::now()->toString(),
               Product::UPDATED_AT => Carbon::now()->toString()
           ],
           [
               Product::ID => Uuid::uuid4()->toString(),
               Product::PRICE => 600.0,
               Product::CODE => '10-02',
               Product::NAME => 'Product 3',
               Product::CATEGORY_ID => $category[0]->id,
               Product::CREATED_AT => Carbon::now()->toString(),
               Product::UPDATED_AT => Carbon::now()->toString()
           ],
       ]);
    }
}
