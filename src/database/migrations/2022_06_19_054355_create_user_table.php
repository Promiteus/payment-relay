<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(User::TABLE_NAME)) {
            Schema::create('users', function (Blueprint $table) {
                $table->string(User::ID, 255)->primary();
                $table->integer(User::CODE)->nullable();
                $table->boolean(User::DISABLES)->default(false);
                $table->string(User::PASSWORD, 255)->nullable();
                $table->string(User::EMAIL, 50)->nullable();
                $table->timestamp(User::REG_DATE)->nullable();
                $table->string(User::REMEMBER_TOKEN, 255)->nullable();
                $table->string(User::USER_NAME, 100)->nullable();
                $table->boolean(User::VERIFIED_EMAIL)->default(false);
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
        Schema::dropIfExists('users');
    }
}
