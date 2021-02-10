<?php

namespace App\Repository;

use App\Entity\Wardrobe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wardrobe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wardrobe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wardrobe[]    findAll()
 * @method Wardrobe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WardrobeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wardrobe::class);
    }

    // /**
    //  * @return Wardrobe[] Returns an array of Wardrobe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Wardrobe
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
