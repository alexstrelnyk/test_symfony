<?php
declare(strict_types=1);

namespace App\Exception;

class CityIsNotValidException extends \Exception
{
    public function __construct()
    {
        parent::__construct("City is not valid");
    }
}
