<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStockLevelCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('update-stock-level')
            ->setDescription('Send stock level update')
            ->addArgument('sku', InputArgument::REQUIRED, 'SKU')
            ->addArgument('qty', InputArgument::REQUIRED, 'QTY')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $x = $this->getApiClient()->discover('oms')
            ->call(new Request('magento.inventory.source_stock_management.update', '1', [
                'stock' => [
                    'sku' => $input->getArgument('sku'),
                    'quantity' => $input->getArgument('qty'),
                    'source_id' => 'P_STORE_1',
                    'type' => 'GOOD',
                ],
            ]))
            ->resolve(1);

        var_dump($x);
    }

}
