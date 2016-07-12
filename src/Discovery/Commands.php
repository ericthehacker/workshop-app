<?php

namespace Magento\Bootstrap\Discovery;

use Symfony\Component\Console\Command\Command;

class Commands implements DiscoveryInterface
{
    /**
     * @return Command[]
     */
    public function discover()
    {
        $files = glob(__DIR__.'/../Command/*.php');

        $commands = [];

        foreach ($files as $file) {
            preg_match('/^.+\/(.+)\.php$/', $file, $matches);
            $className = '\Magento\Bootstrap\Command\\'.$matches[1];

            $reflectionClass = new \ReflectionClass($className);

            if (!$reflectionClass->isInstantiable()) {
                continue;
            }

            $commands[] = new $className();
        }

        return $commands;
    }
}
