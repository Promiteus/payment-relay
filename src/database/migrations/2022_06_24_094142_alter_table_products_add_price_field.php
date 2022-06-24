<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AlterTableProductsAddPriceField extends Migration
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
                $table->double(Product::PRICE)->default(0);
                $table->string(Product::DESCRIPTION)->nullable()->change();
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
                $table->dropColumn(Product::PRICE);
            });
        }
    }
}
