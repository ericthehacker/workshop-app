<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\BootstrapTests\Model\Repository;

use Magento\Bootstrap\Model\Entity\Sku;
use Magento\Bootstrap\Model\Repository\SkuRepository;

class SkuRepositoryTest extends RepositoryTestCase
{
    /**
     * @var SkuRepository
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->repository = $this->setUpORM()
            ->getRepository(Sku::class);
    }

    /**
     * Make sure proper instance of Sku Repository is instantiated.
     */
    public function testRepositoryOverriden()
    {
        $this->assertInstanceOf(SkuRepository::class, $this->repository);
    }

    /**
     * Make sure all fields of sku entity properly persisted.
     */
    public function testSkuPersisted()
    {
        $sku = $this->createSku();

        $this->repository->save($sku);
        $this->repository->clear();

        $loaded = $this->repository->find('SKU001');

        $this->assertNotSame($sku, $loaded, 'It seems like object was loaded from identity map');
        $this->assertEquals($sku->getSku(), $loaded->getSku());
        $this->assertEquals($sku->getName(), $loaded->getName());
    }

    /**
     * @return Sku
     */
    private function createSku()
    {
        return new Sku('SKU001', 'Foo');
    }
}
