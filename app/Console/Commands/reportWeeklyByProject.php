<?php

namespace App\Console\Commands;

use App\Mail\Report;
use App\User;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class reportWeeklyByProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weeklybyproject';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and emails a weekly effort report for each user in the system.';

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
            $now = CarbonImmutable::now();
            // These are default setting for Carbon. I'm not sure if they can be altered.
            $monday = $now->startOfWeek();
            // dump($monday->toDateString());
            $sunday = $now->endOfWeek();

            $eventsCollection = $user->events()
            ->whereBetween('event_date', [$monday->toDateString(), $sunday->toDateString()])
            ->with(['project', 'task'])
            ->get();

            $this->info($user->name.': '.$eventsCollection->count());

            if ($eventsCollection->count() === 0) {
                Log::info('No weekly report sent to user Id '.$user->id.': 0 Events.');
                continue; // Move on to the next user.
            }

            $totalTime = $eventsCollection->sum('duration');

            // By Project, then Task.
            $eventsCollection = $eventsCollection->sortBy(['project.name', 'task.name'])->groupBy(['project.name', 'task.name']);
            $groupDisplayArray = ['Project', 'Task'];
            $reportHeader = 'Hours for week of '.$monday->format('l F j, Y').' through '.$sunday->format('l F j, Y');

            //$monday->format('l F j, Y')

            Mail::to($user)->send(new Report($eventsCollection, $groupDisplayArray, $totalTime, $now, $reportHeader));
        }

        return 0;
    }
}
