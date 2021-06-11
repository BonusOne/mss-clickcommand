<?php

namespace App\Repository;

use App\Entity\RedirectData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RedirectData|null find($id, $lockMode = null, $lockVersion = null)
 * @method RedirectData|null findOneBy(array $criteria, array $orderBy = null)
 * @method RedirectData[]    findAll()
 * @method RedirectData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RedirectDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RedirectData::class);
    }

    /**
     * @param $value int
     * @return RedirectData[] Returns an array of RedirectData objects
     */

    public function findBySmartCampaign($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_smart_campaign = :val')
            ->andWhere('r.deleted = 0')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * @param $value int
     * @return RedirectData[] Returns an array of RedirectData objects
     */

    public function findByTracklyCampaign($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_trackly_campaign = :val')
            ->andWhere('r.delete = 0')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * @param $value int
     * @return RedirectData[] Returns an array of RedirectData objects
     */

    public function findBySatakuCampaign($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.id_sataku_campaign = :val')
            ->andWhere('r.delete = 0')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /*
    public function findOneBySomeField($value): ?RedirectData
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
