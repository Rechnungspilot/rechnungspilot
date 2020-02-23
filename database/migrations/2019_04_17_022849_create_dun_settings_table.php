<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDunSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dun_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('dun_id');
            $table->unsignedInteger('level_id');

            $table->unsignedTinyInteger('action')->default(0);
            $table->unsignedTinyInteger('waiting_days')->default(0);
            $table->boolean('attach_invoice')->default(false);
            $table->string('email')->default('');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('dun_id')->references('id')->on('receipts');
            $table->foreign('level_id')->references('id')->on('dun_levels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
