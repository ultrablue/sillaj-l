<?php

namespace App\Console\Commands;

use App\Mail\Report;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// use Illuminate\Suupport\Fac

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a test command to experiment with commands.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info($this->signature.' ran!');

        $allUsers = User::all();
        foreach ($allUsers as $user) {
            $now = now();

            $eventsCollection = $user->events()
            ->whereBetween('event_date', [$now->toDateString(), $now->toDateString()])
            ->with(['task', 'project'])
            ->get();

            $this->info($user->name.': '.$eventsCollection->count());

            if ($eventsCollection->count() === 0) {
                Log::info('No daily report sent to user Id '.$user->id.': 0 Events.');
                continue;
            }

            $totalTime = $eventsCollection->sum('duration');

            // By Project, then Task.
            $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
            $groupDisplayArray = ['Project', 'Task'];
            $reportHeader = 'Hours for '.$now->format('l F j, Y');

            Mail::to($user)->send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now, $reportHeader));
        }

        return 0;
    }
}
