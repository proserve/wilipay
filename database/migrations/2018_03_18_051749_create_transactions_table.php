<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type', ['commission', 'payment', 'exchange', 'transfer']);
            $table->float('amount');
            $table->string('purpose');
            $table->string('beneficiary')->nullable();
            $table->string('payer')->nullable();
            $table->integer('sold_id')->unsigned();
            $table->foreign('sold_id')->references('id')->on('solds')->onDelete('cascade');
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
        Schema::dropIfExists('transactions');
    }
}
