<?php

namespace Magento\BootstrapTests\Model\Repository;

use Magento\Bootstrap\Model\Entity\ShipmentRequest;
use Magento\Bootstrap\Model\Repository\ShipmentRequestRepository;

class ShipmentRequestRepositoryTest extends RepositoryTestCase
{
    /**
     * @var ShipmentRequestRepository
     */
    private $repository;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->repository = $this->setUpORM()
            ->getRepository(ShipmentRequest::class);
    }

    /**
     * Make sure proper instance of Shipment Repository is instantiated.
     */
    public function testRepositoryOverriden()
    {
        $this->assertInstanceOf(ShipmentRequestRepository::class, $this->repository);
    }

    /**
     * Make sure all fields of shipment request properly persisted.
     */
    public function testShipmentRequestPersisted()
    {
        $request = $this->createShipmentRequest();

        $this->repository->save($request);
        $this->repository->clear();

        $loaded = $this->repository->find(1);

        $this->assertNotSame($request, $loaded, 'It seems like object was loaded from identity map');
        $this->assertEquals($request->getAmount(), $loaded->getAmount());
        $this->assertEquals($request->getNumber(), $loaded->getNumber());
        $this->assertEquals($request->getOrderId(), $loaded->getOrderId());
        $this->assertEquals($request->getStatus(), $loaded->getStatus());
    }

    /**
     * Make sure persisting of shipment request is cascaded to lines.
     */
    public function testShipmentRequestLinesCascaded()
    {
        $request = $this->createShipmentRequest();

        $this->repository->save($request);
        $this->repository->clear();

        $loaded = $this->repository->find(1);

        $this->assertNotSame($request, $loaded, 'It seems like object was loaded from identity map');
        $this->assertEquals($request->getLines()->count(), $loaded->getLines()->count());
    }

    /**
     * @return ShipmentRequest
     */
    private function createShipmentRequest()
    {
        return (new ShipmentRequest('1'))
            ->setAmount(100.0)
            ->setNumber('1')
            ->setOrderId('X009064001')
            ->setStatus('PENDING')
            ->addLine('1', 'SKU001', 10, 15.0)
            ->addLine('2', 'SKU002', 1, 20.0)
        ;
    }
}
