<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('company')->nullable();
            $table->string('country')->nullable();
            $table->string('email')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('number')->nullable();
            $table->string('postcode')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('mobilenumber')->nullable();
            $table->string('faxnumber')->nullable();
            $table->boolean('email_receipt')->nullable();
            $table->string('bankname')->nullable();
            $table->string('bic')->nullable();
            $table->string('iban')->nullable();
            $table->string('vatnumber')->nullable();
            $table->string('euvatnumber')->nullable();
            $table->string('website')->nullable();
            $table->integer('revenue')->default(0);
            $table->unsignedInteger('invoice_term_id')->default(0)->index();
            $table->unsignedInteger('expense_term_id')->default(0)->index();
            $table->decimal('lat', 10, 7)->default(0);
            $table->decimal('lng', 10, 7)->default(0);
            $table->dateTime('coordinates_set_at')->nullable();
            $table->unsignedMediumInteger('debitor_account_number')->nullable();
            $table->unsignedMediumInteger('creditor_account_number')->nullable();
            $table->string('company_number')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
