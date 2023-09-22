<?php
declare(strict_types=1);

namespace App\Service;

use App\DTO\ShippingAddressDTO;
use App\Entity\Client;
use App\Entity\ShippingAddress;
use App\Exception\ClientNotFoundException;
use App\Exception\ShippingAddressNotFoundException;
use App\Exception\UnableToCreateShippingAddressException;
use App\Exception\UnableToDeleteDefaultShippingAddressException;
use App\Exception\UnableToDeleteShippingAddressException;
use App\Exception\UnableToEditShippingAddressException;
use App\Exception\UnableToSetDefaultShippingAddressException;
use App\Repository\ClientRepository;
use App\Repository\ShippingAddressRepository;
use App\ValueObject\Address;
use App\ValueObject\ClientId;
use App\ValueObject\ShippingAddressId;

/**
 * @method expects(\PHPUnit\Framework\MockObject\Matcher\AnyInvokedCount $any)
 */
class ShippingAddressService implements ShippingAddressServiceInterface
{
    /**
     * @var ClientRepository
     */
    private $clientRepository;

    /**
     * @var ShippingAddressRepository
     */
    private $shippingAddressRepository;

    /**
     * @param ClientRepository $clientRepository
     * @param ShippingAddressRepository $shippingAddressRepository
     */
    public function __construct(ClientRepository $clientRepository, ShippingAddressRepository $shippingAddressRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->shippingAddressRepository = $shippingAddressRepository;
    }

    /**
     * @param ShippingAddressDTO $dto
     * @param string $clientId
     * @return string
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function add(ShippingAddressDTO $dto, string $clientId)
    {
        $client = $this->getClient($clientId);
        $shippingAddressId = ShippingAddressId::next();
        /** @var ShippingAddressId $shippingAddressId */
        /** @var Client $client */
        $shippingAddress = new ShippingAddress(
            $shippingAddressId,
            new Address(
                $dto->country,
                $dto->city,
                $dto->zipCode,
                $dto->street
            ),
            $client
        );

        try {
            $client->addShippingAddress($shippingAddress);
            $this->shippingAddressRepository->save($shippingAddress);
        } catch (\Exception $e) {
            throw new UnableToCreateShippingAddressException;
        }

        return true;
    }

    /**
     * @param string $clientId
     * @return mixed
     * @throws \Exception
     */
    public function list(string $clientId)
    {
        $client = $this->getClient($clientId);
        $list = $this->shippingAddressRepository->findAllByClient($client);

        return $list;
    }

    /**
     * @param ShippingAddressDTO $dto
     * @param string $clientId
     * @param string $shippingAddressId
     * @return bool
     * @throws \Exception
     */
    public function edit(ShippingAddressDTO $dto, string $clientId, string $shippingAddressId)
    {
        $client = $this->getClient($clientId);
        $shippingAddress = $this->getShippingAddress($shippingAddressId, $client);

        try {
            $shippingAddress->country($dto->country);
            $shippingAddress->city($dto->city);
            $shippingAddress->zipCode($dto->zipCode);
            $shippingAddress->street($dto->street);
            $this->shippingAddressRepository->save($shippingAddress);
        } catch (\Exception $e) {
            throw new UnableToEditShippingAddressException;
        }

        return true;
    }

    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function default(string $clientId, string $shippingAddressId)
    {
        $client = $this->getClient($clientId);
        $shippingAddress = $this->getShippingAddress($shippingAddressId, $client);

        try {
            $list = $this->shippingAddressRepository->findByClient($client);

            /** @var ShippingAddress $item */
            foreach ($list as $item) {
                $item->unsetDefault();
                $this->shippingAddressRepository->save($item);
            }

            $shippingAddress->setDefault();
            $this->shippingAddressRepository->save($shippingAddress);
        } catch (\Exception $e) {
            throw new UnableToSetDefaultShippingAddressException;
        }

        return true;
    }

    /**
     * @param string $clientId
     * @param string $shippingAddressId
     * @return bool
     * @throws \Exception
     */
    public function remove(string $clientId, string $shippingAddressId)
    {
        $client = $this->getClient($clientId);
        $shippingAddress = $this->getShippingAddress($shippingAddressId, $client);

        if ($shippingAddress->getDefault()) {
            throw new UnableToDeleteDefaultShippingAddressException;
        }

        try {
            $client->removeShippingAddress($shippingAddress);
            $this->shippingAddressRepository->remove($shippingAddress);
        } catch (\Exception $e) {
            throw new UnableToDeleteShippingAddressException;
        }

        return true;
    }

    /**
     * @param string $clientId
     * @return mixed
     * @throws ClientNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    private function getClient(string $clientId)
    {
        $clientId = ClientId::fromString($clientId);
        /** @var ClientId $clientId */
        $client = $this->clientRepository->findById($clientId);

        if (!$client) {
            throw new ClientNotFoundException;
        }

        return $client;
    }

    /**
     * @param string $shippingAddressId
     * @param Client $client
     * @return mixed
     * @throws ShippingAddressNotFoundException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    private function getShippingAddress(string $shippingAddressId, Client $client)
    {
        $shippingAddressId = ShippingAddressId::fromString($shippingAddressId);
        /** @var ShippingAddressId $shippingAddressId */
        $shippingAddress = $this->shippingAddressRepository->findByIdAndClient($shippingAddressId, $client);

        if (!$shippingAddress) {
            throw new ShippingAddressNotFoundException;
        }

        return $shippingAddress;
    }
}
