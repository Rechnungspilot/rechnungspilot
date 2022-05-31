<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_articles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('item_id');

            $table->decimal('unit_value', 12, 4)->default(0);
            $table->decimal('unit_price', 15, 6)->default(0);
            $table->decimal('unit_cost', 15, 6)->default(0);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_articles');
    }
}
