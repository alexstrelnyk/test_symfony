<?php
declare(strict_types=1);

namespace App\Exception;

class UnableToSetDefaultShippingAddressException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to set default shipping address");
    }
}
