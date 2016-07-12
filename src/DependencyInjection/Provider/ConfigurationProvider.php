<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Bootstrap\DependencyInjection\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Yaml\Yaml;

class ConfigurationProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['bootstrap.config'] = function () use ($pimple) {
            $yaml = file_get_contents(__DIR__.'/../../../app/config/parameters.yml');

            return Yaml::parse($yaml);
        };
    }
}
