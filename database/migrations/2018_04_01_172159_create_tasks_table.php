<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true)->comment('FK for the Users table.');
            $table->string('name', 255)->nullable()->comment('The name of the Task.'); // 'task' in the classic version.
            $table->string('description', 2048)->nullable()->comment('A description of this Task.'); // 'rem' in the classic version.
            $table->boolean('display')->comment('Is this used?'); // Is this used in the classic version?
            $table->boolean('use_in_reports')->comment('Show this Task in reports?');
            $table->boolean('share')->comment('Share this Task with all users?');
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
            $table->dropForeign(['task_id']);
        });
        Schema::dropIfExists('tasks');
    }
}
