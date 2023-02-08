<?php

namespace App\Jobs;

class ListProductParts extends BaseJob
{
    public function execute()
    {
        $parts = [];

        $this->parser->registerCallback(
            '/export_full/items/item/parts/part/item',
            function ($parser, $element) use (&$parts) {
                $parts[] = (string)$element->attributes()['name'];
            }
        );

        $this->parser->registerCallback(
            '/export_full/items/item',
            function ($parser, $element) use (&$parts) {
                $code = (string)$element->attributes()['code'];
                $name = (string)$element->attributes()['name'];
                if (empty($parts)) {
                    $this->output->write(str_pad("Processing: $code", 80) . "\r");
                } else {
                    $this->output->writeln(str_pad("<info>$name</info>", 80) . "\r");
                    array_walk(
                        $parts, 
                        fn($part) => $this->output->writeln("=> <comment>$part</comment>")
                    );
                    $parts = [];
                }
            }
        );

        $this->parser->parse();
    }
}
