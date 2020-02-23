<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDunLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dun_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('item_id')->default(0)->index();

            $table->unsignedTinyInteger('level');
            $table->unsignedMediumInteger('amount')->default(0);
            $table->text('description')->default(0);

            $table->string('name');

            $table->unsignedTinyInteger('action')->default(0);
            $table->unsignedTinyInteger('waiting_days')->default(0);
            $table->boolean('attach_invoice')->default(false);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('levels');
    }
}
