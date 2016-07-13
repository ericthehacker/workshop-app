<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('ping')
            ->setDescription('Send ping')
            ->addArgument('payload', InputArgument::OPTIONAL, 'Payload');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApiClient()
            ->discover('oms')
            ->publish(new Request('ping', '0', [
                'payload' => $input->getArgument('payload')
            ]));
    }
}
