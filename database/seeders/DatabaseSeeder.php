<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // Order is important here.
            // First, you need Users.
            // Then, Tasks, which need a User.
            // Then, Projects, which need a User and some Tasks.
            // Finally, we need to associate some Tasks with Projects.
            UsersTableSeeder::class,
            TasksTableSeeder::class,
            ProjectsTableSeeder::class,
            EventsTableSeeder::class,
            ProjectTaskTableSeeder::class
        ]);
    }
}
