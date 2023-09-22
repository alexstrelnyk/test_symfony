<?php
declare(strict_types=1);

namespace App\ValueObject;

class Address
{
    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $zipCode;

    /**
     * @var string
     */
    private $street;

    /**
     * @param $country
     * @param $city
     * @param $zipCode
     * @param $street
     */
    public function __construct($country, $city, $zipCode, $street)
    {
        $this->country = $country;
        $this->city = $city;
        $this->zipCode = $zipCode;
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function country()
    {
        return $this->country;
    }

    /**
     * @return string
     */
    public function city()
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function zipCode()
    {
        return $this->zipCode;
    }

    /**
     * @return string
     */
    public function street()
    {
        return $this->street;
    }
}
