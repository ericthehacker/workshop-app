<?php

namespace Magento\Bootstrap\Discovery\Command;

use Magento\Bootstrap\DependencyInjection\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowTable extends ContainerAwareCommand
{
    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $className;

    /**
     * ShowTable constructor.
     *
     * @param string $entityName
     * @param string $className
     */
    public function __construct($entityName, $className)
    {
        $this->entityName = $entityName;
        $this->className = $className;
        parent::__construct('query:'.strtolower($this->entityName).':all');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entites = $this->getEntityManager()
            ->getRepository($this->className)
            ->findAll();

        $properties = $this->getProperties($this->className);

        $table = new Table($output);
        $table->setHeaders($properties);

        foreach ($entites as $entity) {
            $values = [];
            foreach ($properties as $property) {
                $values[] = $this->getPropertyValue($entity, $property);
            }
            $table->addRow($values);
        }

        $table->render();
    }

    /**
     * @param string $className
     *
     * @return string[]
     */
    private function getProperties($className)
    {
        $entity = new $className(null);

        $reflect = new \ReflectionClass($entity);
        $properties = $reflect->getProperties();

        return array_map(function (\ReflectionProperty $property) {
            return $property->getName();
        }, $properties);
    }

    /**
     * @param $entity
     * @param $property
     *
     * @return array
     */
    protected function getPropertyValue($entity, $property)
    {
        $r = new \ReflectionObject($entity);
        $p = $r->getProperty($property);
        $p->setAccessible(true);

        return $p->getValue($entity);
    }
}
