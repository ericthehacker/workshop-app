<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use Magento\Bootstrap\Model\Entity\Sku;
use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends AbstractCommand
{
    protected function configure()
    {
        $this->setName('consume')
            ->setDescription('Consume AMQP messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this
            ->bind('magento.catalog.product_management.updated', function(Request $request) {
                $product = $request->getArgument('product');
                $repository = $this->getSkuRepository();

                $sku = $repository->find($product['sku']) ?: new Sku($product['sku']);
                $sku->setName($product['name'][0]['value']);

                $repository->save($sku);
            })
            ->consume();
    }
}
