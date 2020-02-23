<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_receipt', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('receipt_id');
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();

            $table->nullableMorphs('receiptable');

            $table->decimal('quantity', 12, 4)->default(0);
            $table->decimal('unit_price', 15, 6)->default(0);
            $table->decimal('discount', 4, 3)->default(0);
            $table->integer('net')->default(0);
            $table->decimal('tax', 4, 3)->default(0);
            $table->integer('gross')->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('receipt_id')->references('id')->on('receipts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_receipt');
    }
}
