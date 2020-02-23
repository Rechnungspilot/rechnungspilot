<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInteractionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('contact_id');
            $table->unsignedBigInteger('interaction_type_id');

            $table->unsignedBigInteger('interaction_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('person_id')->nullable();

            $table->nullableMorphs('interactionable');

            $table->dateTime('at');

            $table->string('name')->nullable();
            $table->text('text')->nullable();

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('contact_id')->references('id')->on('contacts');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('interaction_id')->references('id')->on('interactions');
            $table->foreign('person_id')->references('id')->on('people');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interactions');
    }
}
