<?php

namespace App\Jobs;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use GuzzleHttp\Client;
use App\Parser\Parser;

abstract class BaseJob
{
    private string $url = 'https://www.retailys.cz/wp-content/uploads/astra_export_xml.zip';
    private string $tempDirectory;
    private string $zipFile;
    private string $dataFile;
    protected OutputInterface $output;
    protected Parser $parser;

    public function __construct(OutputInterface $output) 
    {
        $this->url = $_ENV['URL'];
        $this->output = $output;
        // $this->tempDirectory = sprintf('%s%s%s', sys_get_temp_dir(), DIRECTORY_SEPARATOR, uniqid());
        $this->tempDirectory = sys_get_temp_dir();
        
        @mkdir($this->tempDirectory);
        $this->fileCheck($this->tempDirectory);

        $this->zipFile = $this->tempDirectory . DIRECTORY_SEPARATOR . 'astra_export_xml.zip';
        $this->dataFile = $this->tempDirectory . DIRECTORY_SEPARATOR . 'export_full.xml';

        if (!file_exists($this->zipFile)) {
            $this->download();
        }

        if (!file_exists($this->dataFile)) {
            $this->extract();
        }

        $this->parser = new $_ENV['PARSER']($this->dataFile);
    }

    public function __destruct() {
        $this->cleanup();
    }

    abstract public function execute();

    private function download() {
        $this->output->writeln("Downloading $this->url");

        $client = new Client();

        $response = $client->get($this->url);
        $content = $response->getBody()->getContents();

        file_put_contents($this->zipFile, $content);
        $this->fileCheck($this->zipFile);
    }

    private function extract(): void
    {
        $this->output->writeln("Extracting $this->zipFile");

        $zip = new \ZipArchive;
        if ($zip->open($this->zipFile) === TRUE) {
            $zip->extractTo($this->tempDirectory);
            $zip->close();
        }
        $this->fileCheck($this->dataFile);
    }

    private function cleanup(): void
    {
        // @unlink($this->zipFile);
        // @unlink($this->dataFile);
        // @rmdir($this->tempDirectory);
    }

    private function fileCheck(string $file): void
    {
        if (!file_exists($file)) {
            $this->output->writeln("<error>Error while creating $this->tempDirectory</error>");
            die;
        }
    }
}
