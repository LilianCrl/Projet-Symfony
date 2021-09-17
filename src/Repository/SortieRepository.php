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

    /**
     * findByExcept : permet de retourner toutes les sorties de la base qui ne sont pas archivés
     *
     * @return array : retourne un tableau de sorties
     */
    public function findByExcept(){
        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat','etat')
            ->andWhere('etat.id NOT IN (7,8)')
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    public function findByFiltre($array){
        $query=$this->createQueryBuilder('sortie')
                    ->innerJoin('sortie.site','site');
        if(isset($array["site"])){
            $query->andWhere('site.id = :val')
                  ->setParameter('val', $array["site"]);
        }
        if(isset($array["dateDebut"])){
          $query->andWhere('sortie.dateHeureDebut BETWEEN :firstDate AND :lastDate')
                ->setParameter('firstDate', $array["dateDebut"])
                ->setParameter('lastDate', $array["dateFin"]);
        }
        if(isset($array["nom"])){
            $query->andWhere('sortie.nom LIKE :search')
                ->setParameter('search', '%'.$array["nom"].'%');
        }
        return $query->getQuery()
                     ->getResult();
    }

    /**
     * findDateInscriptSup : retourne les sorties dont l'etat est a "ouvert" et dont la date limite d'inscription
     *                      est passée
     * @return array: tableau de sorties
     */
    public function findDateInscriptSup(){

        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat','etat')
            ->andWhere('etat.id = 2')
            ->andWhere("TIMEDIFF(sortie.dateLimiteInscription ,:today)<0")
            ->setParameter('today',new \DateTime('now'))
            ->getQuery()
            ->getResult();
    }

    /**
     * findByDateSupMois : permet de retourner toutes les sorties dont la date d'evenement est inférieur a un mois passée
     *
     * @return array : tableau de sorties
     */
    public function findByDateSupMois(){
        $dateArchive = new \DateTime('now');
        $dateArchive->setTimezone(new \DateTimeZone('Europe/Paris'));
        $dateArchive= $dateArchive->sub( new \DateInterval('P1M'));
        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat','etat')
            ->andWhere('etat.id NOT IN (7,8)')
            ->andWhere('sortie.dateHeureDebut < :mois')
            ->setParameter('mois',$dateArchive)
            ->getQuery()
            ->getResult();
    }

    /**
     * findByDatPasse : permet de retourner toutes les sorties dont la date d'evenement est inférieur a un mois passée
     *
     * @return array : tableau de sorties
     */
    public function findByDatePasse(){
        $today = new \DateTime('now');
        $test =new \DateTime('now');
        $today->setTimezone(new \DateTimeZone('Europe/Paris'));

        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat','etat')
            ->andWhere('etat.id IN (2,3,4)')
            ->andWhere("DATEADD(sortie.dateHeureDebut,sortie.duree,'MINUTE') < :today")
            ->setParameter('today',$today)
            ->andWhere('sortie.dateHeureDebut > :mois')
            ->setParameter('mois',$test->sub( new \DateInterval('P1M')))
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int|mixed|string
     */
    public function findByDateBetween(){
        $today = new \DateTime('now');
        $today->setTimezone(new \DateTimeZone('Europe/Paris'));
        return $this->createQueryBuilder('sortie')
            ->innerJoin('sortie.etat','etat')
            ->andWhere('etat.id IN (2,3)')
            ->andWhere("TIMEDIFF(sortie.dateHeureDebut,:date1)<0")
            ->setParameter('date1',$today)
            ->andWhere("TIMEDIFF(DATEADD(sortie.dateHeureDebut,sortie.duree,'MINUTE'),:today)>0")
            ->setParameter('today',$today)
            ->getQuery()
            ->getResult();
    }

}






    /* version 1 pour eviter les if a répétition dans le controller
     * public function createQueryFiltre(){

        return $this->createQueryBuilder('sortie')
                    ->innerJoin('sortie.site','site');
    }

    public function addFiltreNom($query, $contient){
        return $query->andWhere('sortie.nom LIKE :val')
                     ->setParameter('val', '%'.$contient.'%');
    }
    public function addFiltreSite($query, $site){
        return $query->andWhere('site.id = :val')
                     ->setParameter('val', $site);
    }
    public function addFiltreDate($query,\DateTime $firstDateTime, \DateTime $lastDateTime )
    {
        return $query->andWhere('sortie.dateHeureDebut BETWEEN :firstDate AND :lastDate')
                     ->setParameter('firstDate', $firstDateTime)
                     ->setParameter('lastDate', $lastDateTime);
    }
    public function executeQuery($query){
        return $query->getQuery()
                     ->getResult();
    }

----------------------------------------------------------------------------------------

    version 2 fonctionnel mais multiplie les if dans le controller.
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

    */

