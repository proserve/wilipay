<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('user_id');
            $table->enum('brand', config('app.card_brands')); // visa , master
            $table->integer('exp_year');
            $table->integer('exp_month');
            $table->string('last4');
            $table->string('country');
//            $table->string('card_id')->unique(); // from stripe
            $table->string('source_id')->unique();; // from stripe
            $table->softDeletesTz();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
