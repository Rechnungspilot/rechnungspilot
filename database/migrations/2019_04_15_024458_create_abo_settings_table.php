<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAboSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abo_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('abo_id');

            $table->boolean('active')->default(false);
            $table->boolean('send_mail')->default(false);
            $table->string('email')->nullable();

            $table->unsignedMediumInteger('interval_value')->default(0);
            $table->string('interval_unit')->nullable();

            $table->date('start_at');
            $table->date('next_at');
            $table->date('last_at')->nullable();
            $table->unsignedMediumInteger('last_count')->default(0);
            $table->unsignedTinyInteger('last_type')->default(0);

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('abo_id')->references('id')->on('receipts');
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
