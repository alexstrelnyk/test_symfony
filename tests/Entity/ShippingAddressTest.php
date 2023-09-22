<?php
declare(strict_types=1);

namespace App\Tests\Entity;

use App\DTO\ShippingAddressDTO;
use App\Entity\Client;
use App\Entity\ShippingAddress;
use App\ValueObject\Address;
use App\ValueObject\ClientId;
use App\ValueObject\ClientName;
use App\ValueObject\ShippingAddressId;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class ShippingAddressTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testDefaultShippingAddress()
    {
        $clientId = ClientId::next();
        /** @var ClientId $clientId */
        $client = $this->createFakerClient($clientId);
        $shippingAddress = $this->createFakerShippingAddress($client);

        $shippingAddress->setDefault();
        $this->assertEquals(true, $shippingAddress->getDefault());

        $shippingAddress->unsetDefault();
        $this->assertEquals(false, $shippingAddress->getDefault());
    }

    /**
     * @param ClientId $clientId
     * @return Client
     */
    private function createFakerClient(ClientId $clientId)
    {
        $faker = Factory::create();

        return new Client(
            $clientId,
            new ClientName($faker->firstName, $faker->lastName)
        );
    }

    /**
     * @param Client $client
     * @return ShippingAddress
     * @throws \Exception
     */
    private function createFakerShippingAddress(Client $client)
    {
        $faker = Factory::create();

        $dto = ShippingAddressDTO::fromArray([
            "country" => $faker->country,
            "city" => $faker->city,
            "zip_code" => $faker->postcode,
            "street" => $faker->streetName
        ]);
        $shippingAddressId = ShippingAddressId::next();

        /** @var ShippingAddressId $shippingAddressId */
        return new ShippingAddress(
            $shippingAddressId,
            new Address(
                $dto->country,
                $dto->city,
                $dto->zipCode,
                $dto->street
            ),
            $client
        );
    }
}
