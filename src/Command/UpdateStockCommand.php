<?php

namespace Magento\Bootstrap\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStockCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('stock:update')
            ->addArgument('sku')
            ->addArgument('quantity');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $topic = 'magento.inventory.source_stock_management.update';
        $type = 'GOOD';
        $source = 'Warehouse_1';

        // ...
    }
}