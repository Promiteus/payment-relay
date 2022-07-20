<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AlterProductsCodeUniqueField extends Migration
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
                $table->unique(Product::CODE, 'product_code_unique')->change();
                $table->boolean(Product::DISABLED)->default(false);
                $table->string(Product::CATEGORY_ID, 200)->nullable();
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
                $table->dropUnique('product_code_unique');
                $table->dropColumn(Product::DISABLED);
                $table->dropColumn(Product::CATEGORY_ID);
            });
        }
    }
}
