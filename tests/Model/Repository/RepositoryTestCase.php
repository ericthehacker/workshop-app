<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\BootstrapTests\Model\Repository;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;

abstract class RepositoryTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    protected function setUpORM()
    {
        if (!in_array('pdo_sqlite', DriverManager::getAvailableDrivers())) {
            $this->markTestSkipped('PDO SQLite driver is not available');
        }

        $path = __DIR__.'/../../../src/Model/Entity';

        $config = Setup::createAnnotationMetadataConfiguration([$path], false, null, null, false);
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
        $this->manager = EntityManager::create($connection, $config);

        $tool = new SchemaTool($this->manager);
        $tool->dropDatabase();
        $tool->createSchema($this->manager->getMetadataFactory()->getAllMetadata());

        return $this->manager;
    }
}
