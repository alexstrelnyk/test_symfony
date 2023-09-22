<?php
declare(strict_types=1);

namespace App\Exception;

class ZipCodeIsNotValidException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Zip code is not valid");
    }
}
