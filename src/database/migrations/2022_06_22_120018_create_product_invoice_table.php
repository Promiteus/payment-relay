<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\ProductInvoice;

class CreateProductInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable(Invoice::TABLE_NAME) && Schema::hasTable(Product::TABLE_NAME)) {
            Schema::create(ProductInvoice::TABLE_NAME, function (Blueprint $table) {
                $table->string('id', 255)->primary();
                $table->string('product_id', 255);
                $table->string('invoice_id', 255);
                $table->foreign(ProductInvoice::INVOICE_ID)
                    ->references(Invoice::ID)
                    ->on(Invoice::TABLE_NAME)
                    ->onDelete('cascade');
                $table->foreign(ProductInvoice::PRODUCT_ID)
                    ->references(Product::ID)
                    ->on(Product::TABLE_NAME)
                    ->onDelete('cascade');
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
        Schema::dropIfExists(ProductInvoice::TABLE_NAME);
    }
}
