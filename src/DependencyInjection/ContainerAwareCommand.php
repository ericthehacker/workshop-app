<?php

namespace Magento\Bootstrap\DependencyInjection;

use Doctrine\ORM\EntityManager;
use Magento\Bootstrap\Model\Entity\ShipmentRequest;
use Magento\Bootstrap\Model\Entity\Sku;
use Magento\Bootstrap\Model\Entity\Stock;
use Magento\Bootstrap\Model\Repository\AbstractRepository;
use Magento\Bootstrap\Model\Repository\ShipmentRequestRepository;
use Magento\Bootstrap\Model\Repository\SkuRepository;
use Magento\Bootstrap\Model\Repository\StockRepository;
use Pimple\Container;
use Seven\Component\MessageBusClient\Client;
use Seven\Component\MessageBusClient\Protocol\AMQP\Consumer;
use Seven\Component\MessageBusClient\ServiceInterface;
use Symfony\Component\Console\Command\Command;

abstract class ContainerAwareCommand extends Command
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Client
     */
    public function getApiClient()
    {
        return $this->container['bootstrap.api_client'];
    }

    /**
     * @return Consumer
     */
    public function getAmqpConsumer()
    {
        return $this->container['bootstrap.amqp.consumer'];
    }

    /**
     * @return ServiceInterface
     */
    public function getApiService()
    {
        return $this->container['bootstrap.api_service'];
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container['entity_manager'];
    }

    /**
     * @param string $entityClass
     *
     * @return AbstractRepository
     */
    public function getRepository($entityClass)
    {
        return $this->getEntityManager()
            ->getRepository($entityClass);
    }

    /**
     * @return SkuRepository
     */
    public function getSkuRepository()
    {
        return $this->getRepository(Sku::class);
    }

    /**
     * @return StockRepository
     */
    public function getStockRepository()
    {
        return $this->getRepository(Stock::class);
    }

    /**
     * @return ShipmentRequestRepository
     */
    public function getShipmentRequestRepository()
    {
        return $this->getRepository(ShipmentRequest::class);
    }
}
