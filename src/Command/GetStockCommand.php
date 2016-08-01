<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 7/28/16
 * Time: 2:40 PM
 */

namespace Magento\Bootstrap\Command;


use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetStockCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('stock:get');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $topic = 'magento.inventory.source_stock_repository.search';


        $payload = [];

        $promise = $this->getApiClient()
            ->discover('oms')
            ->call( new Request($topic, '1.0', $payload) );

        $promise->resolve(10);
    }


}