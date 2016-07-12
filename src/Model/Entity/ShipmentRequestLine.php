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
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(length=128)
     */
    private $id;

    /**
     * @var ShipmentRequest
     *
     * @ORM\ManyToOne(targetEntity="\Magento\Bootstrap\Model\Entity\ShipmentRequest", inversedBy="lines")
     */
    private $shipmentRequest;

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
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @param ShipmentRequest $request
     * @param string          $id
     * @param string          $sku
     */
    public function __construct(ShipmentRequest $request, $id, $sku)
    {
        $this->shipmentRequest = $request;
        $this->id = $id;
        $this->sku = $sku;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ShipmentRequest
     */
    public function getShipmentRequest()
    {
        return $this->shipmentRequest;
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
     * @return ShipmentRequestLine
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
     * @return ShipmentRequestLine
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

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
     * @return ShipmentRequestLine
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
