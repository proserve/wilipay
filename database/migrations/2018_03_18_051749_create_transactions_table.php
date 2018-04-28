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
            // TODO: create different db table for every transaction types
            $table->uuid('id');
            $table->primary('id');
            $table->uuid('account_id');
            $table->uuid('beneficiary_id')->nullable();
            $table->uuid('card_id')->nullable();
            $table->enum('type', ['commission', 'payment', 'exchange_sell', 'exchange_buy', 'transfer', 'between_users', 'card_top_up']);
            $table->float('amount');
            $table->string('purpose');
            $table->string('beneficiary')->nullable();
            $table->string('payer')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('card_id')->references('id')->on('cards');
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
