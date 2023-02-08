<?php

namespace App\Jobs;

use Symfony\Component\Console\Output\OutputInterface;
use App\Common\Storage;
use App\Parser\Parser;

abstract class BaseJob
{
    protected OutputInterface $output;
    protected Parser $parser;

    public function __construct(OutputInterface $output) 
    {
        $this->output = $output;
        $dataFile = Storage::getInstance()->withOutput($output)->getFile();
        $this->parser = new $_ENV['PARSER']($dataFile);
    }

    abstract public function execute();
}
