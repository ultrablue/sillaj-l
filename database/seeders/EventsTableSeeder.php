<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\User;
use App\Project;
use App\Event;
use App\Task;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all the Users from the database.
        $users = User::all();

        // Seed Events.
        // TODO is there a way to do a callback rather than a closure?
        $users->each(function ($user) {

            // Get all Projects that are shared or that 'belong' the the User.
            $projects = Project::where('user_id', '=', $user->id)
                ->orWhere('share', '=', TRUE)
                ->get();

            // Get each Task created by this User and create some Events associated with that Task.
            $user->tasks->each(function ($task) use ($user, $projects) {
                Event::factory()->count(2)->create([
                    'user_id'    => $user->id,
                    'task_id'    => $task->id,
                    // This probably shouldn't be random, but I'm not sure what to make it for now.
                    'project_id' => $projects->random()->id
                ]);

                // Create some Events with just durations, no start or end times.
                Event::factory()->count(2)->duration()->create([
                    'user_id'    => $user->id,
                    'task_id'    => $task->id,
                    'project_id' => $projects->random()->id
                ]);
            });

            // Get all of the shared Tasks that were NOT created by this User so that we can make some Events with those Tasks.
            $sharedTasks = Task::where([
                ['share', '=', '1'],
                ['user_id', '<>', $user->id]
            ]);

            $sharedTasks->each(function ($task) use ($user, $projects) {
                Event::factory()->count(2)->create([
                    'user_id'    => $user->id,
                    'task_id'    => $task->id,
                    'project_id' => $projects->random()->id
                ]);
            });
        });
    }
}
