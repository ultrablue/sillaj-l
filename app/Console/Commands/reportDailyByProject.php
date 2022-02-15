<?php

namespace App\Console\Commands;

use App\Mail\Report;
use App\User;
use App\Event;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

// use Illuminate\Suupport\Fac

class ReportDailyByProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:dailybyproject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and emails a daily effort report for each user in the system.';

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
        // $this->info($this->signature . ' ran!');

        $allUsers = User::all();
        foreach ($allUsers as $user) {
            // TODO Make this a CarbonImmutable, please.
            // $now = now();

            // TODO Let's move this to the User Model, please.
            // $eventsCollection = $user->events()
            // ->whereBetween('event_date', [$now->toDateString(), $now->toDateString()])
            // ->with(['task', 'project'])
            // ->get();


            $startTime = $endTime = new CarbonImmutable();
            // TODO Use a Scope here instead of a seperate method.
            $eventsCollection = Event::rollupByProjectForUser($startTime, $endTime, $user);
            // TODO count() doesn't count Events in the ROLL UP table.

            // foreach ($eventsCollection->toArray() as $event) {
            //     dump($event);
            // }

            // exit;

            $this->info($user->name . ' (id ' . $user->id . '): ' . $eventsCollection->count() .  ' Events');

            // If the current User doesn't have any Events, carry on, carry on.
            if ($eventsCollection->count() === 0) {
                Log::info('No daily report sent to user Id ' . $user->id . ': 0 Events.');
                continue;
            }

            $totalTime = $eventsCollection->sum('duration');
            $this->info($totalTime / 3600);
            // exit;
            // By Project, then Task.
            // $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
            $groupDisplayArray = ['Project', 'Task'];
            $reportHeader = 'Hours for ' . $startTime->format('l F j, Y');

            Mail::to($user)->send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $startTime, $reportHeader));
        }

        return 0;
    }
}
