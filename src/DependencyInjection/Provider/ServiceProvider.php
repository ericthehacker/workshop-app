<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\DependencyInjection\Provider;

use Magento\Bootstrap\Printer\ServicePrinterDecorator;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Seven\Component\MessageBusClient\Binding\CallbackBinding;
use Seven\Component\MessageBusClient\Client;
use Seven\Component\MessageBusClient\Message\Request;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @todo Define your bindings in this method.
     *
     * @param Container       $app
     * @param CallbackBinding $binding
     */
    public function setup(Container $app, CallbackBinding $binding)
    {
        $binding
            ->on('magento.catalog.product_management.updated', '0', function (Request $request) use ($app) {
                //$sku = ...

                $app['entity_manager']->persist($sku);
                $app['entity_manager']->flush($sku);
            })
            ->on('ping', '0', function (Request $request) use ($app) {

            });
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['bootstrap.api_service'] = function () use ($pimple) {
            $bindings = new CallbackBinding();

            $this->setup($pimple, $bindings);

            $service = $pimple['bootstrap.api_client']->define('workshop-app')
                ->bind($bindings);

            return new ServicePrinterDecorator($service);
        };
    }

    /**
     * @param Container $app
     * @return Client
     */
    private function getApiClient(Container $app)
    {
        return $app['bootstrap.api_client'];
    }
}
