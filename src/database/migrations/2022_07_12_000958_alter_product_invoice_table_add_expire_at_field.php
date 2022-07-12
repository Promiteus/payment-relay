<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ProductInvoice;

class AlterProductInvoiceTableAddExpireAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(ProductInvoice::TABLE_NAME)) {
            Schema::table(ProductInvoice::TABLE_NAME, function (Blueprint $table) {
                $table->timestamp(ProductInvoice::EXPIRED_OPT_AT)->nullable();
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
        if (Schema::hasTable(ProductInvoice::TABLE_NAME)) {
            Schema::table(ProductInvoice::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(ProductInvoice::EXPIRED_OPT_AT);
            });
        }
    }
}
