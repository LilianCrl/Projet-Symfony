<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Utils\UpdateDate;
use phpDocumentor\Reflection\Types\Mixed_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/",name="app_")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/",name="main")
     */
    public function main():Response{
        return $this->redirectToRoute('app_home');
    }


    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request,SortieRepository $repository, SiteRepository $repoSite, UpdateDate $update): Response
    {
        //Repository pour mettre a jour toutes les tables par rapport aux dates
        $update->updateSorties();

       //$query=$repository->createQueryFiltre(); pour la version 1
        $sites =$repoSite->findAll(); //pour récupérer tout les sites dans le select
        $sortiesO = $this->getUser()->getSorties();

        //récupération des valeur dans les champs
        $tabSorties= [];
        $tabFiltre =[];
        $site = $request->get('site_choise');
        $contient = $request->get('search');
        $date = new \DateTime($request->get('firstDate'));
        $dateL =new \DateTime($request->get('lastDate'));

        if( $request->get('firstDate')&& $request->get('lastDate')  ){
            //$sorties =$repository->findByDate($date,$dateL); version 2 a chaque if la sorties est initialisé
            //$query=$repository->addFiltreDate($query, $date, $dateL); version 1
            $tabFiltre["dateDebut"]=$date;
            $tabFiltre["dateFin"]=$dateL;
        }
        if ($contient) {
            //$sorties =$repository->findByWord($contient); version 2 a chaque if la sorties est initialisé
            //$query=$repository->addFiltreNom($query,$contient); version 1
            $tabFiltre["nom"]=$contient;
        }
        if ($site ) {
            //$sorties =$repository->findBySite($site); version 2 a chaque if la sorties est initialisé
            //$query=$repository->addFiltreSite($query,$site); version 1
            $tabFiltre["site"]=$site;
        }
        if ($contient || $site || ( $request->get('firstDate')&& $request->get('lastDate'))) {

            $sorties = $repository->findByFiltre($tabFiltre);
            //$sorties=$repository->executeQuery($query);version 1 la sorties est initialisé a la fin avec la requetegit adddd
        }
        else{

            $sorties = $repository->findByExcept();
        }


        if($request->get("organizer-outing")){

            foreach ($sorties as $uneSortie){
                if($uneSortie->getOrganisateur()->getId() == $this->getUser()->getId()){
                    if(!in_array($uneSortie, $tabSorties)){
                        array_push($tabSorties,$uneSortie);
                    }
                }
            }

        }
        if($request->get("registered-outing")){

            foreach ($sorties as $uneSortie){
                foreach ($uneSortie->getParticipantsInscrit() as $unParticipant){
                     if($unParticipant()->getId() == $this->getUser()->getId()){
                         if(!in_array($uneSortie, $tabSorties)){
                             array_push($tabSorties,$uneSortie);
                         }
                          break;
                     }
                }
            }

        }
        if($request->get("notRegistered-outing")){

            foreach ($sorties as $uneSortie){
                $flag=true;
                foreach ($uneSortie->getParticipantsInscrit() as $unParticipant){
                    if($unParticipant->getId() == $this->getUser()->getId()){
                        $flag=false;
                        break;

                    }
                }
                if($flag){
                    if(!in_array($uneSortie, $tabSorties)){
                        array_push($tabSorties,$uneSortie);
                    }
                }
            }

        }
        if($request->get("past-outing")){

            foreach ($sorties as $uneSortie){
                if($uneSortie->getEtat()->getId() == 5 ){
                    if(!in_array($uneSortie, $tabSorties)){
                        array_push($tabSorties,$uneSortie);
                    }
                }
            }

        }
        if(!empty($tabSorties)){

                $sorties=$tabSorties;


        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'sorties'=>$sorties , 'sites'=>$sites
        ]);
    }
    /**
     * @Route("/user", name="user")
     */
    public function user(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
