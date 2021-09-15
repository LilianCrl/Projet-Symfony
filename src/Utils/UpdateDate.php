<?php

namespace App\Utils;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * UpdateDate : Service qui permet de mettre a jour les sorties en suivant la date du jour
 *
 */
class UpdateDate
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var SortieRepository
     */
    private $sortieRepo;

    /**
     * @var EtatRepository
     */
    private $etatRepo;

    public function __construct(EntityManagerInterface $manager,SortieRepository $repository,EtatRepository $repoEtat){
        $this->manager = $manager;
        $this->sortieRepo = $repository;
        $this->etatRepo = $repoEtat;
    }

    /**
     * updateSorties : permet de mettre a jour letat de l'ensemble des sorties de la base
     *                  appelle chaque methode d'update
     */
    public function updateSorties(){
        $this->updateEtat($this->sortieRepo->findDateInscriptSup(),3);
        $this->updateEtat($this->sortieRepo->findByDateSupMois(),8);
        $this->updateEtat($this->sortieRepo->findByDateInfMois(),5);
    }

    /**
     * updateDate : permet de mettre a jour l'etat de plusieurs sorties avec l'id de l'etat mis en paramètre
     *              on récupère l'eta grace a son id, on moidifie l'etat de chaque sortie, enfin on met a jour la base
     *
     * @param $sorties : tableau de sorties
     * @param $idEtat : id de l'etat
     */
    private function updateEtat($sorties, $idEtat){
        $unEtat = $this->etatRepo->find($idEtat);

        foreach ($sorties as $uneSortie){
            $uneSortie->setEtat($unEtat);
        }
        $this->manager->flush();
    }


}