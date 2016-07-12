<?php

namespace Magento\Bootstrap\Model\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Magento\Bootstrap\Model\Repository\ShipmentRequestRepository")
 * @ORM\Table(name="shipment_request")
 */
class ShipmentRequest
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(length=128)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(length=128, name="order_id")
     */
    private $orderId;

    /**
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(length=128)
     */
    private $status;

    /**
     * @var Collection|ShipmentRequestLine[]
     *
     * @ORM\OneToMany(targetEntity="\Magento\Bootstrap\Model\Entity\ShipmentRequestLine", mappedBy="shipmentRequest", cascade={"all"})
     */
    private $lines;

    /**
     * @param string $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->lines = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     *
     * @return ShipmentRequest
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param string $orderId
     *
     * @return ShipmentRequest
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     *
     * @return ShipmentRequest
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     *
     * @return ShipmentRequest
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @param string $id
     * @param string $sku
     * @param int    $quantity
     * @param float  $amount
     *
     * @return $this
     */
    public function addLine($id, $sku, $quantity, $amount)
    {
        $line = new ShipmentRequestLine($this, $id, $sku);
        $line->setAmount($amount);
        $line->setQuantity($quantity);

        $this->lines[] = $line;

        return $this;
    }

    /**
     * @return Collection|ShipmentRequestLine[]
     */
    public function getLines()
    {
        return $this->lines;
    }
}
