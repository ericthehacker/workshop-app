<?php

namespace Magento\Bootstrap\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Magento\Bootstrap\Model\Repository\SkuRepository")
 * @ORM\Table(name="sku")
 */
class Sku
{
    /**
     * @ORM\Id
     * @ORM\Column(length=128)
     */
    private $sku;

    /**
     * @ORM\Column(length=128)
     */
    private $name;

    /**
     * @param string $sku
     *
     * @return Sku
     */
    public function __construct($sku)
    {
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Sku
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
