<?php

namespace Magento\Bootstrap\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetStockCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('stock:get');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $topic = 'magento.inventory.source_stock_repository.search';

        // ...
    }
}