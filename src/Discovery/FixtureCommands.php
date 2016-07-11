<?php

namespace Magento\Bootstrap\Discovery;

use Magento\Bootstrap\Discovery\Command\SimulateMessage;
use Pimple\Container;

class FixtureCommands implements DiscoveryInterface
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function discover()
    {
        $commands = [];
        foreach ($this->container['bootstrap.fixture_commands.config']['commands'] as $key => $config) {
            $commands[] = new SimulateMessage(
                $this->container['bootstrap.fixture.loader'],
                $this->container['bootstrap.amqp.client'],
                $config
            );
        }

        return $commands;
    }
}
