<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */

    public function findBySite($value)
    {
        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.site','site')
            ->andWhere('site.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findByWord($value)
    {
        return $this->createQueryBuilder('sortie')
            ->andWhere('sortie.nom LIKE :val')
            ->setParameter('val', '%'.$value.'%')
            ->getQuery()
            ->getResult()
            ;
    }
    public function findByDate(\DateTime $firstDateTime, \DateTime $lastDateTime)
    {
        return $this->createQueryBuilder('sortie')
            ->andWhere('sortie.dateHeureDebut BETWEEN :firstDate AND :lastDate')
            ->setParameter('firstDate', $firstDateTime)
            ->setParameter('lastDate', $lastDateTime)
            ->getQuery()
            ->getResult()
            ;
    }
/*
 *   $sql =$this->createQueryBuilder('sortie')
            ->innerJoin('sortie.site','site');

        foreach ($arrayFiltre as $key=>$value)
        {
            $sql->andWhere($key.'= :$val' )
            ->setParameter('val' , $value);

        }
        return $sql->getQuery()->getResult();
 */

    /*
    public function findOneBySomeField($value): ?Sortie
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
