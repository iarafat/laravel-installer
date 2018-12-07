<?php

namespace App\Commands\Spark\Installation;

use App\Commands\Spark\NewCommand;
use Symfony\Component\Process\Process;

class CreateLaravelProject
{
    /**
     * @var NewCommand
     */
    protected $command;

    /**
     * @var string
     */
    protected $name;

    /**
     * Create a new installation helper instance.
     *
     * @param  NewCommand  $command
     * @param  string  $name
     * @return void
     */
    public function __construct(NewCommand $command, $name)
    {
        $this->name = $name;
        $this->command = $command;
    }

    /**
     * Run the installation helper.
     *
     * @return void
     */
    public function install()
    {
        $process = new Process('laravel new:laravel ' . $this->name);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->setTimeout(null)->run(function ($type, $line) {
            $this->command->line($line);
        });
    }
}
