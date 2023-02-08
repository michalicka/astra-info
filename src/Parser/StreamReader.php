<?php

namespace App\Parser;

use Hobnob\XmlStreamReader\Parser as StreamParser;

class StreamReader implements Parser 
{
    private string $xmlFile;
    private StreamParser $xmlParser;

    public function __construct(string $xmlFile)
    {
        $this->xmlFile = $xmlFile;
        $this->xmlParser = new StreamParser();
    }

    public function registerCallback(string $path, callable $callback): void
    {
        $this->xmlParser->registerCallback($path, $callback);
    }

    public function parse(): void
    {
        $this->xmlParser->parse(fopen($this->xmlFile, 'r'));
    }
}
