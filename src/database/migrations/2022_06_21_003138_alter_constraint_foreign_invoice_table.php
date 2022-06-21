<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;
use App\Models\User;

/**
 * Class AlterConstraintForeignInvoiceTable
 */
class AlterConstraintForeignInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(Invoice::TABLE_NAME) && Schema::hasTable(User::TABLE_NAME)) {
            Schema::table(Invoice::TABLE_NAME, function (Blueprint $table) {
                $table->foreign(Invoice::USER_ID)
                    ->on(User::TABLE_NAME)
                    ->references(User::ID)
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
        if (Schema::hasTable(Invoice::TABLE_NAME) && Schema::hasTable(User::TABLE_NAME)) {
            Schema::table(Invoice::TABLE_NAME, function (Blueprint $table) {
                $table->dropForeign([Invoice::USER_ID]);
            });
        }
    }
}
