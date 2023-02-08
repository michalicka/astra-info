<?php

namespace Tests;

use App\Common\Storage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class StorageTest extends TestCase
{
    public function testGetInstance()
    {
        $dotenv = new Dotenv();
        $dotenv->load(__DIR__.'/../.env');

        $storage1 = Storage::getInstance();
        $storage2 = Storage::getInstance();

        $this->assertInstanceOf(Storage::class, $storage1);
        $this->assertSame($storage1, $storage2);
    }

    public function testWithOutput()
    {
        $storage = Storage::getInstance();
        $output = new BufferedOutput();

        $storage = $storage->withOutput($output);

        $this->assertSame($output, $storage->getOutput());
    }

}
