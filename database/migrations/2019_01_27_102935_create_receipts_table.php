<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');

            $table->unsignedInteger('company_id');
            $table->unsignedInteger('contact_id')->nullable();
            $table->unsignedInteger('term_id')->nullable();
            $table->unsignedInteger('receipt_id')->nullable();
            $table->unsignedInteger('final_invoice_id')->nullable();
            $table->unsignedInteger('latest_dun_id')->nullable();
            $table->uuid('uuid')->unique();

            $table->integer('net')->default(0);
            $table->integer('tax_value')->default(0);
            $table->integer('discount_value')->default(0);
            $table->integer('gross')->default(0);
            $table->integer('outstanding')->default(0);
            $table->unsignedInteger('number');
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->text('text_above')->nullable();
            $table->text('text_below')->nullable();
            $table->text('text')->nullable();
            $table->string('subject')->nullable();
            $table->date('date');
            $table->date('date_due')->nullable();

            $table->boolean('is_partial')->default(false);

            $table->string('latest_status_type')->nullable();
            $table->unsignedInteger('latest_status_id')->default(0);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->foreign('receipt_id')->references('id')->on('receipts');
            $table->foreign('final_invoice_id')->references('id')->on('receipts');
            $table->foreign('latest_dun_id')->references('id')->on('receipts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receipts');
    }
}
