<?php
declare(strict_types=1);

namespace App\Exception;

class CountryIsNotValidException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Country is not valid");
    }
}
