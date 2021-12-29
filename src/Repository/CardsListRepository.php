<?php

namespace App\Repository;

use App\Entity\CardsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CardsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardsList[]    findAll()
 * @method CardsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardsListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardsList::class);
    }

    // /**
    //  * @return CardsList[] Returns an array of CardsList objects
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
    public function findOneBySomeField($value): ?CardsList
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
