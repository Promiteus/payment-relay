<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\GrantedAuthority;

/**
 * Class CreateGrantedAuthorityTable
 */
class CreateGrantedAuthorityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable(GrantedAuthority::TABLE_NAME)) {
            Schema::create(GrantedAuthority::TABLE_NAME, function (Blueprint $table) {
                $table->string(GrantedAuthority::ID, 255);
                $table->string(GrantedAuthority::USER_ID, 255);
                $table->string(GrantedAuthority::ROLE, 20)->nullable();
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
        if (!Schema::hasTable(GrantedAuthority::TABLE_NAME)) {
            Schema::dropIfExists(GrantedAuthority::TABLE_NAME);
        }
    }
}
