<?php

namespace Magento\Bootstrap\Discovery;

use Magento\Bootstrap\Discovery\Command\ShowTable;

class ShowCommands implements DiscoveryInterface
{
    /**
     * {@inheritdoc}
     */
    public function discover()
    {

        $files = glob(__DIR__ . '/../Model/Entity/*.php');

        $commands = [];

        foreach ($files as $file) {
            preg_match('/^.+\/(.+)\.php$/', $file, $matches);
            $entityName = $matches[1];
            $className = '\Magento\Bootstrap\Model\Entity\\' . $entityName;
            $commands[] = new ShowTable($entityName, $className);
        }

        return $commands;
    }
}
