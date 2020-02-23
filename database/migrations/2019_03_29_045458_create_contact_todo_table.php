<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactTodoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_todo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('todo_id');
            $table->unsignedInteger('contact_id');
            $table->unsignedInteger('contact_person_id')->default(0)->index();

            $table->text('address')->default('');

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('todo_id')->references('id')->on('todos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_todo');
    }
}
