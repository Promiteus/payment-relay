<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;

class AlterInvoiceAddPurchaseCodeField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(Invoice::TABLE_NAME)) {
            Schema::table(Invoice::TABLE_NAME, function (Blueprint $table) {
                $table->integer(Invoice::PURCHASE_CODE)
                    ->default(0)
                    ->after(Invoice::COMMENT);
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
        if (Schema::hasTable(Invoice::TABLE_NAME)) {
            Schema::table(Invoice::TABLE_NAME, function (Blueprint $table) {
                $table->dropColumn(\App\Models\Invoice::PURCHASE_CODE);
            });
        }
    }
}
