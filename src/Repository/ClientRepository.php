<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Client;
use App\ValueObject\ClientId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ClientRepository extends ServiceEntityRepository implements ClientRepositoryInterface
{
    /**
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @param ClientId $clientId
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findById(ClientId $clientId)
    {
        return $this->createQueryBuilder('client')
            ->where('client.uuid = :clientId')
            ->setParameter('clientId', $clientId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
