<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');

            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->uuid('uuid')->nullable();
            $table->rememberToken();
            $table->string('address')->nullable();
            $table->string('bankname')->nullable();
            $table->string('bic')->nullable();
            $table->string('city')->nullable();
            $table->string('firstname')->nullable();
            $table->string('iban')->nullable();
            $table->string('lastname')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('number')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('postcode')->nullable();
            $table->string('hex_color_code')->nullable();
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
        Schema::dropIfExists('users');
    }
}
