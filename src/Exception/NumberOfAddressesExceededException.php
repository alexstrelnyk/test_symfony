<?php
declare(strict_types=1);

namespace App\Exception;

class NumberOfAddressesExceededException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Number of addresses exceeded");
    }
}
