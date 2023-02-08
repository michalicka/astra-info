<?php

namespace App\Common;

use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp\Client;

class Storage
{
    private static $instance;
    private string $url;
    private ?string $tempDirectory = null;
    private ?string $zipFile = null;
    private ?string $dataFile = null;
    protected OutputInterface $output;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }

    private function __clone() {}

    private function __construct() 
    {
        $this->url = $_ENV['URL'] ?? '';
        $this->tempDirectory = sprintf('%s%s%s', sys_get_temp_dir(), DIRECTORY_SEPARATOR, uniqid());
        
        @mkdir($this->tempDirectory);
        $this->fileCheck($this->tempDirectory);

        $this->zipFile = $this->tempDirectory . DIRECTORY_SEPARATOR . 'astra_export_xml.zip';
        $this->dataFile = $this->tempDirectory . DIRECTORY_SEPARATOR . 'export_full.xml';
    }

    public function withOutput(OutputInterface $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function getFile(): string
    {
        if (!file_exists($this->zipFile)) {
            $this->download();
        }

        if (!file_exists($this->dataFile)) {
            $this->extract();
        }

        return $this->dataFile;
    }

    public function __destruct() {
        $this->cleanup();
    }

    private function download() {
        $this->getOutput()->writeln("Downloading $this->url");

        $client = new Client();

        $response = $client->get($this->url);
        $content = $response->getBody()->getContents();

        file_put_contents($this->zipFile, $content);
        $this->fileCheck($this->zipFile);
    }

    private function extract(): void
    {
        $this->getOutput()->writeln("Extracting $this->zipFile");

        $zip = new \ZipArchive;
        if ($zip->open($this->zipFile) === TRUE) {
            $zip->extractTo($this->tempDirectory);
            $zip->close();
        }
        $this->fileCheck($this->dataFile);
    }

    private function cleanup(): void
    {
        if ($this->zipFile) @unlink($this->zipFile);
        if ($this->dataFile) @unlink($this->dataFile);
        if ($this->tempDirectory) @rmdir($this->tempDirectory);
        $this->getOutput()->writeln("Temporary folder $this->tempDirectory removed");
    }

    private function fileCheck(string $file): void
    {
        if (!file_exists($file)) {
            $this->getOutput()->writeln("<error>Error while creating $this->tempDirectory</error>");
            die;
        }
    }
}
