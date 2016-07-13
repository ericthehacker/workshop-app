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
            ->setDescription('Send a ping to the message bus')
            ->addArgument('payload', InputArgument::OPTIONAL, 'Payload');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $promise = $this->getApiClient()
            ->discover('oms')
            ->call(new Request('ping', '0', [
                'payload' => $input->getArgument('payload'),
            ]))
        ;

        $response = $promise->resolve(10);

        var_dump($response->getValue());
    }

}
