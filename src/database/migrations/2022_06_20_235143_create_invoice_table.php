<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;

/**
 * Class CreateInvoiceTable
 */
class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(Invoice::TABLE_NAME)) {
            Schema::create(Invoice::TABLE_NAME, function (Blueprint $table) {
                $table->string(Invoice::ID, 255)->primary();
                $table->string(Invoice::USER_ID, 255);
                $table->string(Invoice::STATUS, 10);
                $table->string(Invoice::CURRENCY, 10);
                $table->double(Invoice::PRICE)->default(1);
                $table->string(Invoice::COMMENT, 200);
                $table->timestamps();
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
            Schema::dropIfExists(Invoice::TABLE_NAME);
        }
    }
}
