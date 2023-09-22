<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ShippingAddress;
use App\ValueObject\ShippingAddressId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ShippingAddressRepository extends ServiceEntityRepository implements ShippingAddressRepositoryInterface
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ShippingAddress::class);
    }

    /**
     * @param ShippingAddress $shippingAddress
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ShippingAddress $shippingAddress)
    {
        $this->_em->persist($shippingAddress);
        $this->_em->flush();
    }

    /**
     * @param Client $client
     * @return mixed
     */
    public function findAllByClient(Client $client)
    {
        return $this->getQuery($client)
            ->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @param Client $client
     * @return mixed
     */
    public function findByClient(Client $client)
    {
        return $this->getQuery($client)
            ->getResult();
    }

    /**
     * @param ShippingAddressId $shippingAddressId
     * @param Client $client
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByIdAndClient(ShippingAddressId $shippingAddressId, Client $client)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.uuid = :shippingAddressId')
            ->andWhere('sa.client = :client')
            ->setParameter('shippingAddressId', $shippingAddressId)
            ->setParameter('client', $client)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param ShippingAddress $shippingAddress
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(ShippingAddress $shippingAddress)
    {
        $this->_em->remove($shippingAddress);
        $this->_em->flush();
    }

    /**
     * @param Client $client
     * @return Query
     */
    private function getQuery(Client $client)
    {
        return $this->createQueryBuilder('sa')
            ->where('sa.client = :client')
            ->setParameter('client', $client)
            ->getQuery();
    }
}
