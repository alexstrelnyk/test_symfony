<?php
declare(strict_types=1);

namespace App\Entity;

use App\Exception\CityIsNotValidException;
use App\Exception\CountryIsNotValidException;
use App\Exception\StreetIsNotValidException;
use App\Exception\ZipCodeIsNotValidException;
use App\ValueObject\Address;
use App\ValueObject\ShippingAddressId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ShippingAddressRepository")
 */
class ShippingAddress
{
    /**
     * @var ShippingAddressId
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $country;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $zipCode;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $street;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="shippingAddresses")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="uuid")
     */
    private $client;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isDefault;

    /**
     * @param ShippingAddressId $uuid
     * @param Address $address
     * @param Client $client
     * @throws \Exception
     */
    public function __construct(
        ShippingAddressId $uuid,
        Address $address,
        Client $client
    )
    {
        $this->uuid = $uuid;
        $this->country($address->country());
        $this->city($address->city());
        $this->zipCode($address->zipCode());
        $this->street($address->street());
        $this->client = $client;
        $this->unsetDefault();
    }

    /**
     * @return ShippingAddressId
     */
    public function getId()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @throws \Exception
     */
    public function country(string $country)
    {
        if (empty($country)) {
            throw new CountryIsNotValidException;
        }

        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @throws \Exception
     */
    public function city(string $city)
    {
        if (empty($city)) {
            throw new CityIsNotValidException;
        }

        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @throws \Exception
     */
    public function zipCode(string $zipCode)
    {
        if (empty($zipCode)) {
            throw new ZipCodeIsNotValidException;
        }

        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $street
     * @throws \Exception
     */
    public function street(string $street)
    {
        if (empty($street)) {
            throw new StreetIsNotValidException;
        }

        $this->street = $street;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return bool
     */
    public function getDefault()
    {
        return $this->isDefault;
    }

    public function setDefault()
    {
        $this->isDefault = true;
    }

    public function unsetDefault()
    {
        $this->isDefault = false;
    }
}
