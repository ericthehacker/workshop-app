<?php

namespace Magento\Bootstrap\Discovery\Command;

use Magento\Bootstrap\Fixture\Loader;
use Seven\Component\MessageBusClient\Client;
use Seven\Component\MessageBusClient\Message\Request;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SimulateMessage extends Command
{
    /**
     * @var Loader
     */
    private $fixtureLoader;

    /**
     * @var Client
     */
    private $client;
    /**
     * @var array
     */
    private $config;

    /**
     * @param Loader $fixtureLoader
     * @param Client $client
     * @param array  $config
     */
    public function __construct(Loader $fixtureLoader, Client $client, array $config)
    {
        $this->fixtureLoader = $fixtureLoader;
        $this->client = $client;
        $this->config = $config;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('bootstrap:'.$this->config['command'])
            ->setDescription($this->config['description'])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $jsonMessage = $this->fixtureLoader->load($this->config['fixture']);
        $message = json_decode($jsonMessage, true);

        if (!empty($this->config['variable'])) {
            $key = array_keys($message)[0];
            $message[$key][$this->config['variable']] = 'R'.microtime(true);
        }

        $this->client->broadcast(new Request($this->config['topic'], '1', $message));
    }
}
