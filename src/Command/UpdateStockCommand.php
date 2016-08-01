<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 7/28/16
 * Time: 1:38 PM
 */

namespace Magento\Bootstrap\Command;


use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateStockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('stock:update')
            ->addArgument('sku')
            ->addArgument('quantity');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $type = 'GOOD';
        $source = 'Warehouse_1';
        $sku = $input->getArgument('sku');
        $qty = $input->getArgument('quantity');

        $command = 'magento.inventory.source_stock_management.update';

        $payload = [
            'stock' => [
                'sku' => $sku,
                'quantity' => (float)$qty,
                'source_id' => $source,
                'type' => $type
            ]
        ];

        $this->getApiClient()
            ->discover('oms')
            ->publish( new Request($command, '1.0', $payload) );

        $output->writeln("OK: $sku -> $qty");
    }


}