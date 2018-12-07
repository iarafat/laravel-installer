<?php

namespace App\Commands\Spark;

use Exception;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class RegisterCommand extends Command
{
    use InteractsWithSparkAPI,
        InteractsWithSparkConfiguration;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'spark:register {token : The API token}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Register an API token with the installer';

    /**
     * Execute the console command.
     *
     * return bool
     */
    public function handle()
    {
        if (! $this->valid($this->argument('token'))) {
            return $this->tokenIsInvalid();
        }

        if (! $this->configExists()) {
            mkdir($this->homePath().'/.spark');
        }

        $this->storeToken($this->argument('token'));
        $this->tokenIsValid();
    }

    /**
     * Determine if the given token is valid.
     *
     * @param  string  $token
     * @return bool
     */
    protected function valid($token)
    {
        try {
            (new HttpClient)->get(
                $this->sparkUrl.'/api/token/'.$token.'/validate',
                ['verify' => __DIR__.'/cacert.pem']
            );

            return true;
        } catch (Exception $e) {
            var_dump($e->getMessage());

            return false;
        }
    }

    /**
     * Inform the user that the token is valid.
     *
     * @return void
     */
    protected function tokenIsValid()
    {
        $this->task("Validating Token", function () {
            return true;
        });

        $this->info("Thanks for registering Spark!");
    }
    /**
     * Inform the user that the token is invalid.
     *
     * @return void
     */
    protected function tokenIsInvalid()
    {
        $this->task("Validating Token", function () {
            return false;
        });

        $this->comment("This API token is invalid.");
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
