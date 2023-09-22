<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\ShippingAddressDTO;

interface ShippingAddressServiceInterface
{
    public function add(ShippingAddressDTO $dto, string $clientId);
    public function list(string $clientId);
    public function edit(ShippingAddressDTO $dto, string $clientId, string $shippingAddressId);
    public function default(string $clientId, string $shippingAddressId);
    public function remove(string $clientId, string $shippingAddressId);
}
