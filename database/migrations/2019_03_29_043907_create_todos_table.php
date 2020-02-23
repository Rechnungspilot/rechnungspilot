<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('creator_id')->nullable();
            $table->unsignedInteger('item_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('completer_id')->nullable();

            $table->nullableMorphs('todoable');

            $table->boolean('completed')->default(false);
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->boolean('full_day')->default(false);
            $table->unsignedInteger('duration')->default(0);
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('note')->nullable();
            $table->unsignedTinyInteger('priority')->default(0);
            $table->string('hex_color_code')->nullable();

            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('completer_id')->references('id')->on('users');
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
        Schema::dropIfExists('todos');
    }
}
