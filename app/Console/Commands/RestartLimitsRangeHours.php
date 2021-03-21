<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\RangeHour;

class RestartLimitsRangeHours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hours:restartlimits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restart all the limits from range hours from its initial value';

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
       $hours = RangeHour::where('state', 1)->get();
       foreach ($hours as $hour) {
            $hour->limit = $hour->limit_pd;
            $hour->update();
        }
    }
}
