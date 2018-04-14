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
            $table->integer('user_id', false, true)->comment('FK for the Users table.');
            $table->foreign('user_id')->references('id')->on('users'); 
            $table->string('name', 255)->comment('The name of the Project.');
            $table->boolean('display')->nullable()->default(1)->comment('Indicates whether this Project should be displayed.');
            $table->string('description', 255)->nullable()->comment('A description of the Project.');
            $table->boolean('share')->nullable()->default(0)->comment('Indicates whether this Project is shared.');
            $table->boolean('use_in_reports')->nullable()->default(1)->comment('Indicates whether this Project should appear in Reports');
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
        // Drop the foreign key from events, else you'll get an error when you rollback.
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
        Schema::dropIfExists('projects');
    }
}
