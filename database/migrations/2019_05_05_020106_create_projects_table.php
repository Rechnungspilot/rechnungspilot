<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('project_group_id')->index();
            $table->unsignedInteger('creator_id')->index();

            $table->string('name');
            $table->text('description')->default('');

            $table->date('start_at')->nullable()->default(null);
            $table->date('due_at')->nullable()->default(null);
            $table->date('archived_at')->nullable()->default(null);

            $table->boolean('private')->default(0);

            $table->string('hex_color_code')->default('');

            $table->unsignedMediumInteger('comments_count')->default(0);
            $table->unsignedMediumInteger('todos_count')->default(0);
            $table->unsignedMediumInteger('incompleted_todos_count')->default(0);
            $table->unsignedMediumInteger('completed_todos_count')->default(0);

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
        Schema::dropIfExists('projects');
    }
}
