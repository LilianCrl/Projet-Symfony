<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request,SortieRepository $repository): Response
    {


        $site = $request->get('site_choise');


        //if $site != null faire appel a ma fonction dans le repo
        if ($site ) {

            $sorties =$repository->findBySite($site);




        }
        else{
            $sorties = $repository->findAll();

        }
        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController',
            'sorties'=>$sorties
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
