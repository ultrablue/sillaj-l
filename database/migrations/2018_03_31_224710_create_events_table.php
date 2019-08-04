<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id', false, true)->comment('FK for the Tasks table.');
            $table->integer('project_id', false, true)->comment('FK for the Projects table.');
            $table->integer('user_id', false, true)->comment('FK for the Users table.');
            $table->time('time_start')->nullable()->comment('The time this Event began.');
            $table->time('time_end')->nullable()->comment('The time this Event ended.');
            $table->integer('duration')->nullable()->comment('The duration of this Event.');
            $table->char('iso_8601_duration', 32)->nullable()->default('PT0S')->comment('The ISO 8601 duration of this Event.');
            $table->date('event_date')->comment('The date of this Event.');
            $table->string('note', 2048)->nullable()->comment('A note for this Event.');
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
        Schema::dropIfExists('events');
    }
}
