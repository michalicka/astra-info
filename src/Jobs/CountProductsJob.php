<?php

namespace App\Jobs;

class CountProductsJob extends BaseJob
{
    public function execute()
    {
        $total = 0;

        $this->parser->registerCallback(
            '/export_full/items/item',
            function ($parser, $element) use (&$total) {
                $total++;
                $code = (string)$element->attributes()['code'];
                $this->output->write(str_pad("Processing: $code", 80) . "\r");
            }
        );

        $this->parser->parse();
        $this->output->writeln(str_pad("<info>Total products count: $total</info>", 80));
    }
}
