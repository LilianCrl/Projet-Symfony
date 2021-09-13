<?php

namespace App\Controller;

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
    public function index(Request $request,SortieRepository $repository): Response
    {
    $actions=[];
        $site = $request->get('site_choise');
        $contient = $request->get('search');
        //if $contient != nul faire appel a ma fonction dans le repo
        if ($contient) {
            $sorties =$repository->findByWord($contient);
        }
        else{
            $sorties = $repository->findAll();
        }
        //if $site != null faire appel a ma fonction dans le repo
       /* if ($site ) {
            $sorties =$repository->findBySite($site);
        }
        else{
            $sorties = $repository->findAll();
        }*/
        array_push($actions,"afficher");

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'sorties'=>$sorties,'actions'=>$actions,
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
