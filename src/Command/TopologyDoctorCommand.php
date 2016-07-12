<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\Command;

use PhpAmqpLib\Channel\AMQPChannel;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TopologyDoctorCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('fix-topology')
            ->setDescription('Make sure AMQP topology is in the right shape, and fixes it if it\'s not');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ch = $this->getAmqpChannel();

        $output->writeln('Delete "inbox" exchange');
        $ch->exchange_delete('inbox');

        $output->writeln('Declare "inbox" exchange');
        $ch->exchange_declare('inbox', 'headers', false, true, false, false, false, null);

        $output->writeln('Delete "outbox" exchange');
        $ch->exchange_delete('outbox');

        $output->writeln('Declare "outbox" exchange');
        $ch->exchange_declare('outbox', 'fanout', false, true, false, false, false, null);

        $output->writeln('Create binding: everything from "outbox" goes to "inbox"');
        $ch->exchange_bind('inbox', 'outbox', '', false, null, null);
    }

    /**
     * @return AMQPChannel
     */
    protected function getAmqpChannel()
    {
        $container = $this->getContainer();

        return $container['bootstrap.amqp.channel'];
    }
}
