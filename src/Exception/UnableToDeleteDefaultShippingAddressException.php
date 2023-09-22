<?php
declare(strict_types=1);

namespace App\Exception;

class UnableToDeleteDefaultShippingAddressException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to delete default shipping address");
    }
}
