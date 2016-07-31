<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
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
        $this->getApiClient()
            ->discover('oms')
            ->publish(new Request($topic, '0', [
                'stock' => [
                    'sku' => $input->getArgument('sku'),
                    'quantity' => $input->getArgument('quantity'),
                    'source_id' => $source,
                    'type' => $type,
                ],
            ]));
    }
}