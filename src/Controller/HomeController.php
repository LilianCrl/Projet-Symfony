<?php

namespace App\Controller;

use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
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
    public function index(Request $request,SortieRepository $repository, SiteRepository $repoSite): Response
    {
       //$query=$repository->createQueryFiltre(); pour la version 1
        $sites =$repoSite->findAll(); //pour récupérer tout les sites dans le select
        $sortiesO = $this->getUser()->getSorties();

        //récupération des valeur dans les champs
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

            $sorties = $repository->findAll();
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
