<?php

namespace App\Jobs;

class CountProductsJob extends BaseJob
{
    private int $total = 0;

    public function execute()
    {
        $this->total = 0;

        $this->parser->registerCallback(
            '/export_full/items/item',
            function ($parser, $element) {
                $this->total++;
                $code = (string)$element->attributes()['code'];
                $this->output->write(str_pad("Processing: $code", 80) . "\r");
            }
        );

        $this->parser->parse();
        $this->output->writeln(str_pad("<info>Total products count: $this->total</info>", 80));
    }

    public function getTotal(): int
    {
        return $this->total;
    }
}
