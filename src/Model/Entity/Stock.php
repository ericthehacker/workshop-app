<?php

namespace Magento\Bootstrap\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Magento\Bootstrap\Model\Repository\StockRepository")
 * @ORM\Table(name="stock")
 */
class Stock
{
    const DEFAULT_TYPE = 'GOOD';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(length=128)
     */
    private $sku;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(length=128)
     */
    private $type;

    /**
     * @param string $sku
     * @param int    $quantity
     * @param string $type
     */
    public function __construct($sku, $quantity, $type = null)
    {
        $this->sku = $sku;
        $this->quantity = $quantity;
        $this->type = $type ?: self::DEFAULT_TYPE;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param string $sku
     *
     * @return Stock
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     *
     * @return Stock
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Stock
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}
