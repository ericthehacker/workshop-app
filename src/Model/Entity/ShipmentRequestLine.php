<?php

namespace Magento\Bootstrap\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="\Magento\Bootstrap\Model\Repository\ShipmentRequestLineRepository")
 * @ORM\Table(name="shipment_request_line")
 */
class ShipmentRequestLine
{
    /**
     * @ORM\Id
     * @ORM\Column(length=128)
     */
    private $id;

    /**
     * @ORM\Column(length=128, name="shipment_request_id")
     */
    private $shipmentRequestId;

    /**
     * @ORM\Column(length=128)
     */
    private $sku;

    /**
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getShipmentRequestId()
    {
        return $this->shipmentRequestId;
    }

    /**
     * @param mixed $shipmentRequestId
     *
     * @return ShipmentRequestLine
     */
    public function setShipmentRequestId($shipmentRequestId)
    {
        $this->shipmentRequestId = $shipmentRequestId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->sku;
    }

    /**
     * @param mixed $sku
     *
     * @return ShipmentRequestLine
     */
    public function setSku($sku)
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     *
     * @return ShipmentRequestLine
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     *
     * @return ShipmentRequestLine
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
