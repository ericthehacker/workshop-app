<?php

namespace Magento\Bootstrap\Provider;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Seven\Component\MessageBusClient\Binding\CallbackBinding;
use Seven\Component\MessageBusClient\Client;
use Seven\Component\MessageBusClient\Encoder\JsonEncoder;
use Seven\Component\MessageBusClient\Message\Request;
use Seven\Component\MessageBusClient\Message\Response;
use Seven\Component\MessageBusClient\Protocol\AMQP;

class AmqpProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['bootstrap.amqp.connection'] = function () use ($pimple) {
            $config = $pimple['bootstrap.config']['amqp'];
            return new AMQPStreamConnection(
                $config['host'],
                $config['port'],
                $config['user'],
                $config['password'],
                $config['vhost']
            );
        };

        $pimple['bootstrap.amqp.channel'] = function () use ($pimple) {
            return $pimple['bootstrap.amqp.connection']->channel();
        };

        $pimple['bootstrap.json_encoder'] = function () use ($pimple) {
            return new JsonEncoder();
        };

        $pimple['bootstrap.amqp.driver'] = function () use ($pimple) {
            return new AMQP\Driver($pimple['bootstrap.amqp.channel'], 'outbox', $pimple['bootstrap.json_encoder']);
        };

        $pimple['bootstrap.amqp.client'] = function () use ($pimple) {
            return new Client($pimple['bootstrap.amqp.driver']);
        };

        $pimple['bootstrap.amqp.service'] = function () use ($pimple) {
            return $pimple['bootstrap.amqp.client']
                ->define('warehouse-management-system');
        };

        $pimple['bootstrap.amqp.consumer'] = function () use ($pimple) {
            return new AMQP\Consumer($pimple['bootstrap.amqp.channel'], $pimple['bootstrap.amqp.service']);
        };
    }
}
