<?php

namespace Magento\Bootstrap\Provider;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class DatabaseProvider implements ServiceProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(Container $pimple)
    {
        $pimple['entity_manager'] = function () use ($pimple) {
            $paths = array(__DIR__ . '/../Model/Entity');
            $dbParams = $pimple['bootstrap.config']['database'];
            $config = Setup::createAnnotationMetadataConfiguration($paths, false, $dbParams['doctrine_proxy_path'], null, false);
            return EntityManager::create($dbParams, $config);
        };
    }
}
