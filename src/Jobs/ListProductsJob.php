<?php

namespace App\Jobs;

class ListProductsJob extends BaseJob
{
    public function execute()
    {
        $this->parser->registerCallback(
            '/export_full/items/item',
            function ($parser, $element) {
                $name = (string)$element->attributes()['name'];
                $this->output->writeln("<info>$name</info>");
            }
        );

        $this->parser->parse();
    }
}
