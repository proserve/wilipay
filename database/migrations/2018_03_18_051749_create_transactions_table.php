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
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('account_id')->unsigned();
            $table->uuid('beneficiary_id')->unsigned()->nullable();
            $table->enum('type', ['commission', 'payment', 'exchange_sell', 'exchange_buy', 'transfer', 'between_users']);
            $table->float('amount');
            $table->string('purpose');
            $table->string('beneficiary')->nullable();
            $table->string('payer')->nullable();
            $table->softDeletesTz();
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('beneficiary_id')->references('id')->on('users');
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
