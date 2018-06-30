<?php

namespace App\Repository;

use App\Entity\Subscribers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subscribers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscribers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscribers[]    findAll()
 * @method Subscribers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscribersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subscribers::class);
    }

//    /**
//     * @return Subscribers[] Returns an array of Subscribers objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Subscribers
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
