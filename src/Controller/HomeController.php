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

        $sites =$repoSite->findAll();
        //dd($this->getUser()->getSorties());

        $sortiesO = $this->getUser()->getSorties();

        $site = $request->get('site_choise');
        $contient = $request->get('search');
        $date = new \DateTime($request->get('firstDate'));
        $dateL =new \DateTime($request->get('lastDate'));


        if( $request->get('firstDate')&& $request->get('lastDate')  ){
            $sorties =$repository->findByDate($date,$dateL);
        }
        //if $contient != nul faire appel a ma fonction dans le repo

        elseif ($contient) {
            $sorties =$repository->findByWord($contient);
        }
        //if $site != null faire appel a ma fonction dans le repo
        elseif ($site ) {
            $sorties =$repository->findBySite($site);
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
