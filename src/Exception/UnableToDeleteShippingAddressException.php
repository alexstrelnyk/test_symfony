<?php
declare(strict_types=1);

namespace App\Exception;

class UnableToDeleteShippingAddressException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to delete shipping address");
    }
}
