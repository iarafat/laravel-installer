<?php

namespace App\Commands\Scaffold;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class NewCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:from-template';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $option = $this->menu('Which template do you want to use?', [
            'spatie/blender: The template from the guys of spatie',
            'fwartner/laravel-template: Preconfigured laravel installation with all goodies ',
            'Turnovers, crumb cake, cinnamon buns, scones',
        ])->open();

        $this->info("You have chosen the option number #$option");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
