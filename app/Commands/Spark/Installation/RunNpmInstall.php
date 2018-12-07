<?php

namespace App\Commands\Spark\Installation;

use App\Commands\Spark\NewCommand;
use Symfony\Component\Process\Process;

class RunNpmInstall
{
    /**
     * @var NewCommand
     */
    protected $command;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand  $command
     * @return void
     */
    public function __construct(NewCommand $command)
    {
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        if (! $this->command->confirm('Would you like to install the NPM dependencies?', true)) {
            return;
        }

        $this->command->info("Installing NPM Dependencies...");

        $process = (new Process('npm set progress=false && npm install', $this->command->path))->setTimeout(null);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->command->line($line);
        });
    }
}
