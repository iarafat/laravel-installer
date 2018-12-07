<?php

namespace App\Commands\Laravel;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use RuntimeException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use ZipArchive;

class NewCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'new:laravel {name : The name of your application} {--dev : Installs the latest "development" release} {--force : Forces install even if the directory already exists}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new Laravel application';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (! class_exists('ZipArchive')) {
            throw new RuntimeException('The Zip PHP extension is not installed. Please install it and try again.');
        }

        $directory = ($this->argument('name')) ? getcwd() . '/' . $this->argument('name') : getcwd();
        if (! $this->option('force')) {
            $this->verifyApplicationDoesntExist($directory);
        }

        $this->info("Crafting application...");

        $version = $this->getVersion($this->option('dev'));
        $this->download($zipFile = $this->makeFilename(), $version)
            ->extract($zipFile, $directory)
            ->prepareWritableDirectories($directory)
            ->cleanUp($zipFile);

        $composer = $this->findComposer();
        $commands = [
            $composer . ' install --no-scripts --quiet',
            $composer . ' run-script post-root-package-install --quiet',
            $composer . ' run-script post-create-project-cmd --quiet',
            $composer . ' run-script post-autoload-dump --quiet',
        ];

        $process = new Process(implode(' && ', $commands), $directory, null, null, null);
        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $process->run(function ($type, $line) {
            $this->line($line);
        });

        $this->task("Installing Laravel", function () {
            return true;
        });
    }

    /**
     * Verify that the application does not already exist.
     *
     * @param  string  $directory
     * @return void
     */
    protected function verifyApplicationDoesntExist($directory)
    {
        if ((is_dir($directory) || is_file($directory)) && $directory != getcwd()) {
            throw new RuntimeException('Application already exists!');
        }
    }

    /**
     * Generate a random temporary filename.
     *
     * @return string
     */
    protected function makeFilename()
    {
        return getcwd().'/laravel_'.md5(time().uniqid()).'.zip';
    }

    /**
     * Download the temporary Zip to the given file.
     *
     * @param  string  $zipFile
     * @param  string  $version
     * @return $this
     */
    protected function download($zipFile, $version = 'master')
    {
        switch ($version) {
            case 'develop':
                $filename = 'latest-develop.zip';
                break;
            case 'master':
                $filename = 'latest.zip';
                break;
        }

        $response = (new Client)->get('http://cabinet.laravel.com/' . $filename);
        file_put_contents($zipFile, $response->getBody());

        return $this;
    }

    /**
     * Extract the Zip file into the given directory.
     *
     * @param  string  $zipFile
     * @param  string  $directory
     * @return $this
     */
    protected function extract($zipFile, $directory)
    {
        $archive = new ZipArchive;
        $archive->open($zipFile);
        $archive->extractTo($directory);
        $archive->close();

        return $this;
    }

    /**
     * Clean-up the Zip file.
     *
     * @param  string  $zipFile
     * @return $this
     */
    protected function cleanUp($zipFile)
    {
        @chmod($zipFile, 0777);
        @unlink($zipFile);
        return $this;
    }

    /**
     * Make sure the storage and bootstrap cache directories are writable.
     *
     * @param  string  $appDirectory
     * @return $this
     */
    protected function prepareWritableDirectories($appDirectory)
    {
        $filesystem = new Filesystem;

        try {
            $filesystem->chmod($appDirectory.DIRECTORY_SEPARATOR . "bootstrap/cache", 0755, 0000, true);
            $filesystem->chmod($appDirectory.DIRECTORY_SEPARATOR . "storage", 0755, 0000, true);
        } catch (IOExceptionInterface $e) {
            $this->warn('"You should verify that the "storage" and "bootstrap/cache" directories are writable.');
        }

        return $this;
    }

    /**
     * Get the version that should be downloaded.
     *
     * @param $version
     * @return string
     */
    protected function getVersion($version)
    {
        if ($version === 'dev') {
            return 'develop';
        }
        return 'master';
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" composer.phar';
        }

        return 'composer';
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
