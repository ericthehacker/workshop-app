<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\BootstrapTests\Model\Repository;

use Magento\Bootstrap\Model\Entity\Stock;
use Magento\Bootstrap\Model\Repository\StockRepository;

class StockRepositoryTest extends RepositoryTestCase
{
    /**
     * @var StockRepository
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->repository = $this->setUpORM()
            ->getRepository(Stock::class);
    }

    /**
     * Make sure proper instance of Stock Repository is instantiated.
     */
    public function testRepositoryOverriden()
    {
        $this->assertInstanceOf(StockRepository::class, $this->repository);
    }

    /**
     * Make sure all fields of stock entity properly persisted.
     */
    public function testStockPersisted()
    {
        $stock = $this->createStock();

        $this->repository->save($stock);
        $this->repository->clear();

        $loaded = $this->repository->find(1);

        $this->assertNotSame($stock, $loaded, 'It seems like object was loaded from identity map');
        $this->assertEquals($stock->getSku(), $loaded->getSku());
        $this->assertEquals($stock->getQuantity(), $loaded->getQuantity());
        $this->assertEquals($stock->getType(), $loaded->getType());
    }

    /**
     * @return Stock
     */
    private function createStock()
    {
        return new Stock('SKU001', 100, 'AWESOME');
    }
}
