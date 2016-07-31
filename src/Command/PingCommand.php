<?php

namespace Magento\Bootstrap\Command;

use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PingCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this->setName('ping')
            ->addArgument('payload');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
       $promise = $this->getApiClient()
           ->discover('workshop-app')
           ->call(new Request('ping', '0', [
                'payload' => $input->getArgument('payload')
           ]));

        $promise->resolve(10);
    }
}
