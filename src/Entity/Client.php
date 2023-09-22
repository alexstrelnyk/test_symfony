<?php
declare(strict_types=1);

namespace App\Entity;

use App\Exception\NumberOfAddressesExceededException;
use App\ValueObject\ClientId;
use App\ValueObject\ClientName;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    const NUMBER_OF_ADDRESSES = 3;

    /**
     * @var ClientId
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @var ShippingAddress[]/ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ShippingAddress", mappedBy="client")
     */
    private $shippingAddresses;

    /**
     * @param ClientId $uuid
     * @param ClientName $clientName
     */
    public function __construct(
        ClientId $uuid,
        ClientName $clientName
    ) {
        $this->uuid = $uuid;
        $this->firstName = $clientName->firstName();
        $this->lastName = $clientName->lastName();
        $this->shippingAddresses = new ArrayCollection();
    }

    /**
     * @return ClientId
     */
    public function getId()
    {
        return $this->uuid;
    }

    /**
     * @param ShippingAddress $shippingAddress
     * @throws \Exception
     */
    public function addShippingAddress(ShippingAddress $shippingAddress)
    {
        if ($this->shippingAddresses->count() >= self::NUMBER_OF_ADDRESSES) {
            throw new NumberOfAddressesExceededException;
        }

        if (!$this->shippingAddresses->count()) {
            $shippingAddress->setDefault();
        }

        $this->shippingAddresses->add($shippingAddress);
    }

    public function getShippingAddresses()
    {
        return $this->shippingAddresses;
    }

    /**
     * @param ShippingAddress $shippingAddress
     */
    public function removeShippingAddress(ShippingAddress $shippingAddress)
    {
        $this->shippingAddresses->removeElement($shippingAddress);
    }
}
