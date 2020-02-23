<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('user_id')->index();

            $table->nullableMorphs('fileable');

            $table->string('name');
            $table->string('extension');
            $table->string('original_name');
            $table->string('filename');
            $table->string('mime');
            $table->integer('size')->default(0);
            $table->boolean('thumbnail')->default(false);
            $table->boolean('preview')->default(false);

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
        Schema::dropIfExists('userfiles');
    }
}
