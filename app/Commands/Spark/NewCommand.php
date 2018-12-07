<?php

namespace App\Commands\Spark;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class NewCommand extends Command
{
    /**
     * The path to the new Spark installation.
     *
     * @var string
     */
    public $path;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:spark {name : The name of your application} {--braintree : Install Braintree versions of the file stubs} {--team-billing : Configure Spark for team based billing}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new Spark application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->path = getcwd()  .'/' . $this->argument('name');
        $installers = [
            Installation\CreateLaravelProject::class,
            Installation\DownloadSpark::class,
            Installation\UpdateComposerFile::class,
            Installation\ComposerUpdate::class,
            Installation\AddCoreProviderToConfiguration::class,
            Installation\RunSparkInstall::class,
            Installation\AddAppProviderToConfiguration::class,
            Installation\RunNpmInstall::class,
            Installation\CompileAssets::class,
        ];

        foreach ($installers as $installer) {
            (new $installer($this, $this->argument('name')))->install();
        }
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
