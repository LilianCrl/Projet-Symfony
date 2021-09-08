<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieFormType;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/sorties",name="app_sorties_")
 */
class SortiesController extends AbstractController
{
    private $em;
    private $repo;

    public function __contstruct(EntityManagerInterface $entityManager,SortieRepository $repository){
        $this->em = $entityManager;
        $this->repo = $repository;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('sorties/index.html.twig', [
            'controller_name' => 'SortiesController',
        ]);
    }

    /**
     * @Route("/ajouter",name="add")
     */
    public function add(Request $request,EntityManagerInterface $manager):Response
    {
        $uneSortie = new Sortie();

        $sortieForm = $this->createForm(SortieFormType::class,$uneSortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

           $manager->persist($uneSortie);
           $manager->flush();
            $this->addFlash('success','Sortie Ajoutés');
            dump($uneSortie);
           return $this->redirectToRoute('app_sorties_add');
        }

        return $this->render('sorties/ajouter.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }
}
