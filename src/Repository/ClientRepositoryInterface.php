<?php
declare(strict_types=1);

namespace App\Repository;

use App\ValueObject\ClientId;

interface ClientRepositoryInterface
{
    public function findById(ClientId $clientId);
}
