<?php

namespace Magento\Bootstrap\Model\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractRepository extends EntityRepository
{
    public function save($entity)
    {
        $this->_em->persist($entity);
        $this->_em->flush($entity);
    }
}
