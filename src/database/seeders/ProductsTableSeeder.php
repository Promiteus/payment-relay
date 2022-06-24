<?php

namespace Database\Seeders;

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

       DB::table(Product::TABLE_NAME)->insert([
           [
               Product::ID => Uuid::uuid4(),
               Product::PRICE => 100.0,
               Product::CODE => '10-00',
               Product::NAME => 'Product 1',
               Product::CREATED_AT => Carbon::now(),
               Product::UPDATED_AT => Carbon::now()
           ],
           [
               Product::ID => Uuid::uuid4(),
               Product::PRICE => 300.0,
               Product::CODE => '10-01',
               Product::NAME => 'Product 2',
               Product::CREATED_AT => Carbon::now(),
               Product::UPDATED_AT => Carbon::now()
           ],
           [
               Product::ID => Uuid::uuid4(),
               Product::PRICE => 600.0,
               Product::CODE => '10-02',
               Product::NAME => 'Product 3',
               Product::CREATED_AT => Carbon::now(),
               Product::UPDATED_AT => Carbon::now()
           ],
       ]);
    }
}
