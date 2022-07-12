<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

/**
 * Class AlterProductTableAddExpirationDaysField
 */
class AlterProductTableAddExpirationDaysField extends Migration
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
                $table->unsignedInteger(Product::EXPIRATION_DAYS)->nullable();
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
                $table->dropColumn(Product::EXPIRATION_DAYS);
            });
        }
    }
}
