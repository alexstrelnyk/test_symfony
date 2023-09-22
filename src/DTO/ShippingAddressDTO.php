<?php
declare(strict_types=1);

namespace App\DTO;

class ShippingAddressDTO
{
    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $zipCode;

    /**
     * @var string
     */
    public $street;

    /**
     * @param $array
     * @return ShippingAddressDTO
     */
    public static function fromArray($array)
    {
        $self = new self();

        $self->country = $array['country'];
        $self->city = $array['city'];
        $self->zipCode = $array['zip_code'];
        $self->street = $array['street'];

        return $self;
    }
}
