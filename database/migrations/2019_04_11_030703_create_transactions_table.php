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
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('account_id');

            $table->string('transactionable_type')->default('');
            $table->unsignedInteger('transactionable_id')->default(0);

            $table->string('type');
            $table->unsignedInteger('amount');
            $table->date('date');
            $table->text('reference')->default('');
            $table->text('text')->default('');
            $table->string('name')->default('');
            $table->string('iban')->default('');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->index([ 'transactionable_type', 'transactionable_id' ]);
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
