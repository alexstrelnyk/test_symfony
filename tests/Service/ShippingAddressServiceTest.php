<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\ShippingAddressDTO;
use App\Entity\Client;
use App\Entity\ShippingAddress;
use App\Exception\UnableToDeleteDefaultShippingAddressException;
use App\Exception\UnableToEditShippingAddressException;
use App\Repository\ClientRepository;
use App\Repository\ShippingAddressRepository;
use App\Service\ShippingAddressService;
use App\ValueObject\Address;
use App\ValueObject\ClientId;
use App\ValueObject\ClientName;
use App\ValueObject\ShippingAddressId;
use Doctrine\Common\Collections\ArrayCollection;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class ShippingAddressServiceTest extends TestCase
{
    private $clientRepository;

    private $shippingAddressRepository;

    private $shippingAddressService;

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function testAdd()
    {
        $clientId = ClientId::next();
        /** @var ClientId $clientId */
        $client = $this->createFakerClient($clientId);

        $dto = $this->createFakerDTO();

        $this->shippingAddressService = $this->createShippingAddressService($client);

        $this->shippingAddressService->add($dto, $clientId->getId());

        $clientShippingAddress = $client->getShippingAddresses()[0];

        $this->assertEquals($dto->country, $clientShippingAddress->getCountry());
        $this->assertEquals($dto->city, $clientShippingAddress->getCity());
        $this->assertEquals($dto->zipCode, $clientShippingAddress->getZipCode());
        $this->assertEquals($dto->street, $clientShippingAddress->getStreet());
        $this->assertEquals($client, $clientShippingAddress->getClient());
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function testEdit()
    {
        $faker = Factory::create();

        $clientId = ClientId::next();
        /** @var ClientId $clientId */
        $client = $this->createFakerClient($clientId);

        $dto = $this->createFakerDTO();

        $this->shippingAddressService = $this->createShippingAddressService($client);

        $this->shippingAddressService->add($dto, $clientId->getId());
        $clientShippingAddress = $client->getShippingAddresses()[0];
        $this->shippingAddressRepository->expects($this->any())
            ->method('findByIdAndClient')
            ->willReturn($clientShippingAddress);

        $this->assertEquals($dto->country, $clientShippingAddress->getCountry());

        $dto->country = $faker->country;

        $this->shippingAddressService->edit($dto, $clientId->getId(), $clientShippingAddress->getId()->getId());
        $clientShippingAddress = $client->getShippingAddresses()[0];
        $this->assertEquals($dto->country, $clientShippingAddress->getCountry());

        $dto->country = "";
        $this->expectException(UnableToEditShippingAddressException::class);
        $this->shippingAddressService->edit($dto, $clientId->getId(), $clientShippingAddress->getId()->getId());
    }

    /**
     * @throws \Exception
     */
    public function testDefault()
    {
        $clientId = ClientId::next();
        /** @var ClientId $clientId */
        $client = $this->createFakerClient($clientId);

        $shippingAddress1 = $this->createFakerShippingAddress($client);
        $client->addShippingAddress($shippingAddress1);
        $this->assertEquals(1, $client->getShippingAddresses()->count());

        $shippingAddress2 = $this->createFakerShippingAddress($client);
        $client->addShippingAddress($shippingAddress2);
        $this->assertEquals(2, $client->getShippingAddresses()->count());

        $this->assertEquals(true, $shippingAddress1->getDefault());
        $this->assertEquals(false, $shippingAddress2->getDefault());

        $this->shippingAddressService = $this->createShippingAddressService($client);

        $this->shippingAddressRepository->expects($this->any())
            ->method('findByClient')
            ->willReturn([$shippingAddress1, $shippingAddress2]);
        $this->shippingAddressRepository->expects($this->any())
            ->method('findByIdAndClient')
            ->willReturn($shippingAddress2);

        $this->shippingAddressService->default($clientId->getId(), $shippingAddress2->getId()->getId());

        $this->assertEquals(false, $shippingAddress1->getDefault());
        $this->assertEquals(true, $shippingAddress2->getDefault());
    }

    /**
     * @throws \Exception
     */
    public function testRemove()
    {
        $clientId = ClientId::next();
        /** @var ClientId $clientId */
        $client = $this->createFakerClient($clientId);

        $shippingAddress1 = $this->createFakerShippingAddress($client);
        $client->addShippingAddress($shippingAddress1);
        $this->assertEquals(1, $client->getShippingAddresses()->count());

        $shippingAddress2 = $this->createFakerShippingAddress($client);
        $client->addShippingAddress($shippingAddress2);
        $this->assertEquals(2, $client->getShippingAddresses()->count());

        $this->shippingAddressService = $this->createShippingAddressService($client);

        $this->shippingAddressRepository->expects($this->any())
            ->method('findByIdAndClient')
            ->willReturn($shippingAddress2);

        $this->shippingAddressService->remove($clientId->getId(), $shippingAddress2->getId()->getId());

        $this->assertEquals(1, $client->getShippingAddresses()->count());

        $this->expectException(UnableToDeleteDefaultShippingAddressException::class);
        $this->shippingAddressService->remove($clientId->getId(), $shippingAddress2->getId()->getId());

        $this->assertEquals(1, $client->getShippingAddresses()->count());
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
        $dto = $this->createFakerDTO();
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

    /**
     * @return ShippingAddressDTO
     */
    private function createFakerDTO()
    {
        $faker = Factory::create();

        return ShippingAddressDTO::fromArray([
            "country" => $faker->country,
            "city" => $faker->city,
            "zip_code" => $faker->postcode,
            "street" => $faker->streetName
        ]);
    }

    /**
     * @param Client $client
     * @return \App\Service\ShippingAddressService
     */
    private function createShippingAddressService(Client $client)
    {
        $this->clientRepository = $this->createMock(ClientRepository::class);
        $this->clientRepository->method("findById")
            ->with($client->getId())
            ->willReturn($client);

        $this->shippingAddressRepository = $this->createMock(ShippingAddressRepository::class);

        return new ShippingAddressService($this->clientRepository, $this->shippingAddressRepository);
    }
}
