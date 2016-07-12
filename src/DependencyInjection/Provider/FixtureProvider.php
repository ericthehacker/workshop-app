<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\DependencyInjection\Provider;

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
