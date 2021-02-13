<?php

namespace App\Repository;

use App\Entity\Cloth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cloth|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cloth|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cloth[]    findAll()
 * @method Cloth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClothRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cloth::class);
    }

    // /**
    //   * @return Cloth[] Returns an array of Cloth objects
    // */
    // public function findBestClothers(int $temperature, int $weather, int $rainLevel){

    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT p
    //         FROM App\Entity\Cloth p
    //         WHERE p.temperature > :temperature AND '
    //     )->setParameter('temperature', $temperature);

    //     // returns an array of Product objects
    //     return $query->getResult();
        
    // }

    // /**
    //  * @return Cloth[] Returns an array of Cloth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cloth
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
