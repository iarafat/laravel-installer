<?php

namespace App\Commands\Spark\Installation;

use App\Commands\Spark\NewCommand;

class AddCoreProviderToConfiguration
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
        $path = $this->command->path.'/config/app.php';

        $contents = file_get_contents($path);

        $contents = str_replace(
            '        App\\Providers\\AppServiceProvider::class,',
            "        Laravel\Spark\Providers\SparkServiceProvider::class,\n        App\Providers\AppServiceProvider::class,",
            $contents
        );

        file_put_contents($path, $contents);
    }
}
