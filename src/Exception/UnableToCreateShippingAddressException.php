<?php
declare(strict_types=1);

namespace App\Exception;

class UnableToCreateShippingAddressException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to create shipping address");
    }
}
