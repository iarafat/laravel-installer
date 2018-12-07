<?php

namespace App\Commands\Spark;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class TokenCommand extends Command
{
    use InteractsWithSparkConfiguration;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'spark:token';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Display the currently registered Spark API token';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Spark API Token: " .$this->readToken());
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
