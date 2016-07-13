<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update:stock')
            ->addArgument('sku')
            ->addArgument('quantity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApiClient()
            ->discover('oms')
            ->publish(new Request('magento.inventory.source_stock_management.update', '0', [
                'stock' => [
                    'sku' => $input->getArgument('sku'),
                    'quantity' => $input->getArgument('quantity'),
                    'source_id' => 'WAREOUSE_1',
                    'type' => 'GOOD',
                ],
            ]));
    }

}
