<?php
declare(strict_types=1);

namespace App\Exception;

class UnableToEditShippingAddressException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Unable to edit shipping address");
    }
}
