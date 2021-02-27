<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('unit_id');
            $table->string('number')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('decimals');
            $table->decimal('unit_price', 15, 6)->default(0);
            $table->decimal('unit_cost', 15, 6)->default(0);
            $table->decimal('tax', 4, 3)->default(0);
            $table->integer('revenue')->default(0);
            $table->unsignedTinyInteger('type')->default(0);
            $table->unsignedInteger('duration')->default(0);
            $table->unsignedMediumInteger('expense_account_number')->default(0);
            $table->unsignedMediumInteger('revenue_account_number')->default(0);
            $table->unsignedMediumInteger('cost_center')->default(0);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('unit_id')->references('id')->on('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
