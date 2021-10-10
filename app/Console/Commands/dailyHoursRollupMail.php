<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class dailyHoursRollupMail extends Command
{
    /**
     * The name and signature of the console command.
     * This is the Daily Rollup; it summerizes a user's daily efforts in terms of time.
     *
     * @var string
     */
    protected $signature = 'reports:rollup-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is the Daily Rollup; it summerizes a user\'s daily efforts in terms of time';

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
        return 0;
    }
}
