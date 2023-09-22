<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ShippingAddress;
use App\ValueObject\ShippingAddressId;

interface ShippingAddressRepositoryInterface
{
    public function save(ShippingAddress $shippingAddress);
    public function findAllByClient(Client $client);
    public function findByClient(Client $client);
    public function findByIdAndClient(ShippingAddressId $shippingAddressId, Client $client);
    public function remove(ShippingAddress $shippingAddress);
}
