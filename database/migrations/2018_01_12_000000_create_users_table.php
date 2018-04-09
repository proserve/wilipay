<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('national_phone')->nullable();
            $table->string('country_prefix')->nullable();
            $table->string('password');
            $table->tinyInteger('verified')->default(0);
            $table->string('email_token')->nullable();
            $table->string('fb_account_kit_id')->nullable()->unique();
            $table->string('stripe_customer_id')->nullable()->unique();
            $table->tinyInteger('blocked')->default(1);
            $table->rememberToken();
            $table->softDeletesTz();
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
        Schema::dropIfExists('users');
    }
}
