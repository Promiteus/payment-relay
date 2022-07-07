<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CategoriesTableSeeder extends Seeder
{
    public const PRODUCT_CATEGORY = 'product';
    public const OPTION_CATEGORY = 'option';
    public const SERVICE_CATEGORY = 'service';

    private array $categories;

    public function __construct()
    {
        $this->categories = [self::PRODUCT_CATEGORY, self::OPTION_CATEGORY, self::SERVICE_CATEGORY];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(Category::TABLE_NAME)->delete();


        $data = collect($this->categories)->map(function($item) {
            return [
                Category::ID => Uuid::uuid4()->toString(),
                Category::NAME => $item,
                Category::DISABLED => false,
                Category::CREATED_AT => Carbon::now()->toString(),
                Category::UPDATED_AT => Carbon::now()->toString()
            ];
        })->toArray();

        DB::table(Category::TABLE_NAME)->insert($data);
    }
}
