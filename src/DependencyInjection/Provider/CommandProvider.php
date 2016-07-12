<?php

namespace Magento\Bootstrap\DependencyInjection\Provider;

use Magento\Bootstrap\DependencyInjection\ContainerAwareCommand;
use Magento\Bootstrap\Discovery\Commands;
use Magento\Bootstrap\Discovery\FixtureCommands;
use Magento\Bootstrap\Discovery\ShowCommands;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Yaml\Yaml;

class CommandProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['bootstrap.fixture_commands.config'] = function () use ($pimple) {
            $yaml = file_get_contents(__DIR__.'/../../../app/config/commands.yml');

            return Yaml::parse($yaml);
        };
        $pimple['bootstrap.command.discovery.fixture_commands'] = function () use ($pimple) {
            return new FixtureCommands($pimple, $pimple['bootstrap.fixture_commands.config']);
        };

        $pimple['bootstrap.command.discovery.commands'] = function () use ($pimple) {
            return new Commands($pimple);
        };

        $pimple['bootstrap.command.discovery.show_commands'] = function () use ($pimple) {
            return new ShowCommands($pimple);
        };

        $pimple['bootstrap.command.discovery'] = function () use ($pimple) {
            return [
                $pimple['bootstrap.command.discovery.commands'],
                $pimple['bootstrap.command.discovery.fixture_commands'],
                $pimple['bootstrap.command.discovery.show_commands'],
            ];
        };

        $pimple['symfony.output'] = function () {
            return new ConsoleOutput();
        };

        $pimple['bootstrap.commands'] = function () use ($pimple) {
            $commands = [];
            foreach ($pimple['bootstrap.command.discovery'] as $discovery) {
                $discoveredCommands = $discovery->discover();
                if (!$discoveredCommands) {
                    continue;
                }
                $commands = array_merge($commands, $discoveredCommands);
            }

            foreach ($commands as $command) {
                if ($command instanceof ContainerAwareCommand) {
                    $command->setContainer($pimple);
                }
            }

            return $commands;
        };

        $pimple['bootstrap.command.app'] = function () use ($pimple) {
            $application = new Application();

            $application->addCommands($pimple['bootstrap.commands']);

            $application->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet(array(
                'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($pimple['entity_manager']),
            )));

            $application->addCommands([
                new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
                new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
                new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
                new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
                new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
                new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
                new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
                new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
                new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
                new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
                new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
                new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
                new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
                new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
                new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand(),
                new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
                new \Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand(),
                new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
            ]);

            return $application;
        };
    }
}
