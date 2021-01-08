<?php

namespace App\Repository;

use App\Entity\ClickStatistics;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ClickStatistics|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClickStatistics|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClickStatistics[]    findAll()
 * @method ClickStatistics[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClickStatisticsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ClickStatistics::class);
    }

    // /**
    //  * @return ClickStatistics[] Returns an array of ClickStatistics objects
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
    public function findOneBySomeField($value): ?ClickStatistics
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param $value int
     * @return ClickStatistics[] Returns an array of ClickStatistics objects
     */

    public function findBySmartInsertion($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_smart_insertion = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $value int
     * @return ClickStatistics[] Returns an array of ClickStatistics objects
     */

    public function findByRedirectId($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.redirect_id = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
