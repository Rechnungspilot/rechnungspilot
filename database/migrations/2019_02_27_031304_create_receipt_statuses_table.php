<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipt_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');

            $table->unsignedInteger('company_id');
            $table->unsignedInteger('receipt_id');
            $table->unsignedInteger('user_id')->default(0)->index();

            $table->date('date');
            $table->text('data');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('receipt_id')->references('id')->on('receipts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
