<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNameColumnUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->string('national_phone')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('country_prefix')->nullable();
            $table->string('fb_account_kit_id')->nullable()->unique();
            $table->tinyInteger('blocked')->default(1);
            $table->dropColumn('name');
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropColumn('national_phone');
            $table->dropColumn('phone');
            $table->dropColumn('country_code');
            $table->dropColumn('blocked');
            $table->string('name');
        });
    }
}
