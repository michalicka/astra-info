<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;
use App\Common\Storage;
use App\Parser\StreamReader;
use App\Jobs\CountProductsJob;

class CountProductsJobTest extends TestCase
{
    public function testExecute()
    {
        $_ENV['PARSER'] = StreamReader::class;

        $storage = $this->createMock(Storage::class);
        $output = new BufferedOutput();
        $dataFile = __DIR__.'/data.xml';

        $storage->method('getOutput')->willReturn($output);
        $storage->method('getFile')->willReturn($dataFile);

        $job = new CountProductsJob($storage);
        $job->execute();

        $this->assertEquals(2, $job->getTotal());
    }
}
