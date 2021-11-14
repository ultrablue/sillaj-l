<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    // Order is important here.
    // First, you need Users.
    // Then, Tasks, which need a User.
    // Then, Projects, which need a User and some Tasks.
    // Finally, we need to associate some Tasks with Projects.
    {
        DB::table('events')->delete();
        DB::table('tasks')->delete();
        DB::table('projects')->delete();
        DB::table('project_task')->delete();
        DB::table('users')->delete();
        $this->call(UsersTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(ProjectsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(ProjectTaskTableSeeder::class);
    }
}
