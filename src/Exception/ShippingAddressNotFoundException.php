<?php
declare(strict_types=1);

namespace App\Exception;

class ShippingAddressNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Shipping address not found");
    }
}
