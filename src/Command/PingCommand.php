<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingCommand extends \Magento\Bootstrap\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('ping')
            ->addArgument('payload');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApiClient()
            ->discover('workshop-app') //can then call service
        ->publish(
            new Request('ping', '0', [
                'payload' => $input->getArguments('payload')
            ]));
    }


}
