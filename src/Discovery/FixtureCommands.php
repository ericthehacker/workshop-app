<?php

namespace Magento\Bootstrap\Discovery;

use Magento\Bootstrap\Discovery\Command\SimulateMessage;
use Pimple\Container;
use Symfony\Component\Console\Command\Command;

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
     * @return Command[]
     */
    public function discover()
    {
        $commands = [];
        foreach ($this->container['bootstrap.fixture_commands.config']['commands'] as $key => $config) {
            $commands[] = new SimulateMessage(
                $this->container['bootstrap.fixture.loader'],
                $this->container['bootstrap.api_client'],
                $config
            );
        }

        return $commands;
    }
}
