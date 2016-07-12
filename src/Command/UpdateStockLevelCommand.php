<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStockLevelCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('update-stock-level') // name will be used to run your command
        ->setDescription('Send stock level update') // description will be shown when you run app/console without arguments
        ->addArgument('sku', InputArgument::REQUIRED, 'SKU') // you can add as many arguments as you need
        ->addArgument('qty', InputArgument::REQUIRED, 'Quantity')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApiClient()->discover('oms')
            ->publish(new Request('magento.inventory.source_stock_management.update', '1', [
                'stock' => [
                    'sku' => $input->getArgument('sku'),
                    'quantity' => $input->getArgument('qty'),
                    'source_id' => 'P_STORE_1',
                    'type' => 'GOOD',
                ],
            ]));
    }
}
