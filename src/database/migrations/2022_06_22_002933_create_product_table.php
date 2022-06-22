<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Invoice;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Product::TABLE_NAME, function (Blueprint $table) {
            $table->string('id', 255)->primary();
            $table->string(Product::NAME, 100);
            $table->string(Product::CODE, 100);
            $table->text(Product::DESCRIPTION);
            $table->string(Product::INVOICE_ID, 255);
            $table->foreign(Product::INVOICE_ID)
                ->references(Invoice::ID)
                ->on(Invoice::TABLE_NAME)
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Product::TABLE_NAME);
    }
}
