<?php

use App\Models\Invoice;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterInvoicesTableAddPayUrlField extends Migration
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
                $table->text(Invoice::PAY_URL)->nullable();
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
                $table->dropColumn(Invoice::PAY_URL);
            });
        }
    }
}
