<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use Magento\Bootstrap\DependencyInjection\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsumeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('consume')
            ->setDescription('Consume AMQP messages');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getAmqpConsumer()
            ->consume();
    }
}
