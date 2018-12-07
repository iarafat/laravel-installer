<?php

namespace App\Commands\Spark\Installation;

use App\Commands\Spark\InteractsWithSparkAPI;
use App\Commands\Spark\InteractsWithSparkConfiguration;
use App\Commands\Spark\NewCommand;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Filesystem\Filesystem;
use ZipArchive;

class DownloadSpark
{
    use InteractsWithSparkAPI, InteractsWithSparkConfiguration;

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
        $this->extractZip($this->downloadZip());

        rename($this->sparkPath(), $this->command->path.'/spark');

        (new Filesystem)->deleteDirectory(
            $this->command->path.'/spark-new'
        );
    }

    /**
     * Download the latest Spark release.
     *
     * @return string
     */
    protected function downloadZip()
    {
        $this->command->info("Downloading Spark...");

        file_put_contents(
            $zipPath = $this->command->path.'/spark-archive.zip',
            $this->zipResponse()
        );

        return $zipPath;
    }

    /**
     * Get the raw Zip response for a Spark download.
     *
     * @return string
     */
    protected function zipResponse()
    {
        $release = $this->latestSparkRelease();

        try {
            return (string) (new HttpClient)->get(
                $this->sparkUrl.'/api/releases/' . $release . '/download?api_token=' . $this->readToken(),
                [
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
                    'verify' => __DIR__.'/../cacert.pem',
                ]
            )->getBody();
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 401) {
                $this->invalidLicense($release);
            }

            throw $e;
        }
    }

    /**
     * Extract the Spark Zip archive.
     *
     * @param  string  $zipPath
     * @return void
     */
    protected function extractZip($zipPath)
    {
        $archive = new ZipArchive;
        $archive->open($zipPath);
        $archive->extractTo($this->command->path.'/spark-new');
        $archive->close();

        @unlink($zipPath);
    }

    /**
     * Get the release directory.
     *
     * @return string
     */
    protected function sparkPath()
    {
        return $this->command->path.'/spark-new/'.basename(
            (new Filesystem)->directories($this->command->path.'/spark-new')[0]
        );
    }

    /**
     * Inform the user that their registered Spark token is invalid.
     *
     * @param $release
     * @return void
     */
    protected function invalidLicense($release)
    {
        $this->command->error('You do not own any licenses for release [' . $release . '].');

        exit(1);
    }
}
