<?php
declare(strict_types=1);

namespace App\Exception;

class ClientNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Client not found");
    }
}
