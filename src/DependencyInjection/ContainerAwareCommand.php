<?php

namespace Magento\Bootstrap\DependencyInjection;

use Pimple\Container;
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

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return \Seven\Component\MessageBusClient\Client
     */
    public function getAmqpClient()
    {
        return $this->container['bootstrap.amqp.client'];
    }

    /**
     * @return \Seven\Component\MessageBusClient\Protocol\AMQP\Consumer
     */
    public function getAmqpConsumer()
    {
        return $this->container['bootstrap.amqp.consumer'];
    }

    public function getAmqpService()
    {
        return $this->container['bootstrap.amqp.service'];
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return $this->container['entity_manager'];
    }

    /**
     * @param $entityClass
     *
     * @return \Magento\Bootstrap\Model\Repository\AbstractRepository
     */
    public function getRepository($entityClass)
    {
        return $this->getEntityManager()
            ->getRepository($entityClass);
    }
}
