<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieFormType;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
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
    public function add(Request $request,EntityManagerInterface $manager,SiteRepository $repository):Response
    {
        //user connecte et donc Organisateur de la sortie

       $user = $this->getUser();
        $uneSortie = new Sortie();
        $repoVille = $manager->getRepository(Ville::class);
        $repoLieu = $manager->getRepository(Lieu::class);
        $repoEtat = $manager->getRepository(Etat::class);
        $site = $repository->find(2);
        $sortieForm = $this->createForm(SortieFormType::class,$uneSortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $etat = $repoEtat->find(1);
            $unLieu = $repoLieu->find($request->get("lieu"));


        dd($site);

           $manager->persist($uneSortie);
           $manager->flush();
            $this->addFlash('success','Sortie Ajoutés');
            dump($uneSortie);
           return $this->redirectToRoute('app_sorties_add');
        }

        return $this->render('sorties/ajouter.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'villes'=>$repoVille->findAll()
        ]);
    }


    /**
     * @Route("/ajax/lieu/{idVille}",name="ajax_lieu")
     */
    public function getLieu($idVille,LieuRepository $repository):Response{
        $data=[];
        $unLieu=[];
        $lieux = $repository->findLieuxByIdVille($idVille);
        foreach ($lieux as $lieu){
            $unLieu['id']=$lieu->getId();
            $unLieu['nom']=$lieu->getNom ();
            $unLieu['cp']=$lieu->getVille()->getCodePostal();
            array_push($data,$unLieu);
        }

        return  $this->json($data,200,[],['groups'=>'jsonLieu']);
    }

    /**
     * @Route("/ajax/adresse/{idLieu}",name="ajax_adresse")
     */
    public function getAdresse($idLieu,LieuRepository $repository):Response{
        $lieu = $repository->find($idLieu);
        return  $this->json($lieu,200,[],['groups'=>'jsonAdresse']);
    }

}
