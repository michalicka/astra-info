<?php

namespace App\Parser;

interface Parser 
{
    public function registerCallback(string $path, callable $callback): void;
    public function parse(): void;
}
