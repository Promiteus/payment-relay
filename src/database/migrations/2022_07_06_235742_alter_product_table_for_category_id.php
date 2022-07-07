<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;

/**
 * Class AlterProductTableForCategoryId
 */
class AlterProductTableForCategoryId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(Product::TABLE_NAME)) {
            Schema::table(Product::TABLE_NAME, function (Blueprint $table) {
                $table
                    ->foreign(Product::CATEGORY_ID)
                    ->on(Category::TABLE_NAME)
                    ->references(Category::ID)
                    ->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable(Product::TABLE_NAME)) {
            Schema::table(Product::TABLE_NAME, function (Blueprint $table) {
                $table->dropForeign([Product::CATEGORY_ID]);
            });
        }

    }
}
