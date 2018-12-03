<?php

namespace App\Repository;

use App\Entity\Loice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Loice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loice[]    findAll()
 * @method Loice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Loice::class);
    }

    // /**
    //  * @return Loice[] Returns an array of Loice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Loice
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
