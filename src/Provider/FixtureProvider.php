<?php

namespace Magento\Bootstrap\Provider;

use Magento\Bootstrap\Fixture\Loader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class FixtureProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['bootstrap.fixture.loader'] = function () {
            return new Loader();
        };
    }
}
