<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

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

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $entities = $this->getEntityManager()
            ->getRepository($this->className)
            ->findAll();

        $properties = $this->getProperties($this->className);

        $table = new Table($output);
        $table->setHeaders($properties);

        foreach ($entities as $entity) {
            $values = [];
            foreach ($properties as $property) {
                $value = $this->getPropertyValue($entity, $property);

                if (is_object($value)) {
                    $value = 'Object<'.get_class($value).'>';
                }

                if (is_array($value)) {
                    $value = 'Array';
                }

                $values[] = $value;
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
        $reflect = new \ReflectionClass($className);
        $properties = $reflect->getProperties();

        return array_map(function (\ReflectionProperty $property) {
            return $property->getName();
        }, $properties);
    }

    /**
     * @param object $entity
     * @param string $property
     *
     * @return mixed
     */
    private function getPropertyValue($entity, $property)
    {
        $r = new \ReflectionObject($entity);
        $p = $r->getProperty($property);
        $p->setAccessible(true);

        return $p->getValue($entity);
    }
}
