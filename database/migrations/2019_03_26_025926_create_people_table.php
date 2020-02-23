<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration
{
    public function up()
    {
        Schema::create('people', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('contact_id');

            $table->string('title')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('email')->nullable();
            $table->string('function')->nullable();
            $table->boolean('default_invoice')->default(false);
            $table->boolean('default_quote')->default(false);
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('contact_id')->references('id')->on('contacts');
        });
    }

    public function down()
    {
        Schema::drop('people');
    }
}
