<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
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

        $promise = $this->getApiClient()
            ->discover('oms')
            ->call(new Request($topic, '0', []));

        $promise->resolve(10);
    }
}