<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Project;
use App\Task;

class ProjectTaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = Project::all();
        $standardTasks = Task::take(4)->get();
        $projects->each(function($project) use ($standardTasks) {
            $project->tasks()->attach($standardTasks->random(2,4));
            $otherUsersSharedTasks=Task::where([
                ['share', '=', 'TRUE'],
                ['user_id', '<>', $project->user_id]
            ])->get();
           $project->tasks()->attach($otherUsersSharedTasks->random(2,4));
        });
    }
}
