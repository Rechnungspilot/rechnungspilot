<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');

            $table->string('accountholdername')->nullable();
            $table->string('address')->nullable();
            $table->string('bankname')->nullable();
            $table->string('bic')->nullable();
            $table->string('city')->nullable();
            $table->string('districtcourt')->nullable();
            $table->string('email')->default('');
            $table->string('euvatnumber')->nullable();
            $table->string('faxnumber')->nullable();
            $table->string('firstname')->nullable();
            $table->string('iban')->nullable();
            $table->string('lastname')->nullable();
            $table->string('name')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('postcode')->nullable();
            $table->string('traderegister')->nullable();
            $table->string('vatnumber')->nullable();
            $table->string('web')->nullable();
            $table->integer('balance')->default(0);
            $table->integer('price')->default(0);
            $table->date('charging_start_at')->nullable();
            $table->date('charging_next_at')->nullable();
            $table->decimal('lat', 10, 7)->default(0);
            $table->decimal('lng', 10, 7)->default(0);
            $table->dateTime('coordinates_set_at')->nullable();
            $table->string('invoice_name_format')->default('');
            $table->string('order_name_format')->default('');
            $table->string('quote_name_format')->default('');
            $table->string('delivery_name_format')->default('');
            $table->string('expense_name_format')->default('');
            $table->string('abo_name_format')->default('');
            $table->boolean('locked')->default(false);
            $table->date('locked_at')->nullable()->default(null);
            $table->unsignedMediumInteger('default_debitor_account_number')->default(0);
            $table->unsignedMediumInteger('default_creditor_account_number')->default(0);
            $table->unsignedTinyInteger('debitor_account_number_mode')->default(0);
            $table->unsignedTinyInteger('creditor_account_number_mode')->default(0);
            $table->unsignedTinyInteger('revenue_account_number_19')->default(0);
            $table->unsignedTinyInteger('revenue_account_number_7')->default(0);
            $table->unsignedTinyInteger('revenue_account_number_0_inland')->default(0);
            $table->unsignedTinyInteger('default_revenue_account_number')->default(0);
            $table->unsignedTinyInteger('default_expense_account_number')->default(0);
            $table->boolean('sales_tax')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
