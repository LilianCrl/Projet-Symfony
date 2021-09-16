<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\SortieFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\ParticipantsRepository;
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
        $uneSortie = new Sortie();
        $repoVille = $manager->getRepository(Ville::class);
        $repoLieu = $manager->getRepository(Lieu::class);
        $repoEtat = $manager->getRepository(Etat::class);

        $sortieForm = $this->createForm(SortieFormType::class,$uneSortie);
        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            //Changement de l'etat suivant le bouton qui a ete soumis
            if($request->get('submit')=="Enregistrer"){
                $etat = $repoEtat->find(1);
            }else{
                $etat = $repoEtat->find(2);
            }

            $uneSortie->setEtat($etat)
                ->setLieu($repoLieu->find($request->get("lieu")))
                ->setOrganisateur($this->getUser())
                ->setSite($this->getUser()->getSite());

           $manager->persist($uneSortie);
           $manager->flush();
            $this->addFlash('success','Sortie Ajoutée');
           return $this->redirectToRoute('app_home');
        }

        return $this->render('sorties/ajouter.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'villes'=>$repoVille->findAll(),
        ]);
    }

    /**
     * @Route("/afficher/{idSortie}",name="afficher_sortie")
     */
    public function showSortie(Request $request,SortieRepository $repository, $idSortie):Response
    {
        $sortie = $repository->find($idSortie);
        return $this->render('sorties/afficher.html.twig',[
            'sortie' =>$sortie
            ]);
    }
/*
    /**
     * @Route("/annuler/{idSortie}",name="annuler")
     */
    /*public function cancel(EntityManagerInterface $manager,SortieRepository $repository,EtatRepository $repoEtat,int $idSortie):Response{
        $uneSortie = $repository->find($idSortie);
        $unEtat = $repoEtat->find(6);
        $uneSortie->setEtat($unEtat);
        $manager->flush();
        $this->addFlash('success','Votre sortie a bien été annulée');
        return $this->redirectToRoute('app_home');
    }*/

    /**
     * @Route("/publier/{idSortie}", name="publier")
     */
    public function publish(EntityManagerInterface $manager,SortieRepository $repository,EtatRepository $repoEtat,int $idSortie):Response{
        $uneSortie = $repository->find($idSortie);
        $unEtat = $repoEtat->find(2);
        $uneSortie->setEtat($unEtat);
        $manager->flush();
        $this->addFlash('success','l\'inscription pour votre sortie est ouverte');
        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/inscrire/{idSortie}",name="inscrire")
     */
    public function suscribe(Request $request,EntityManagerInterface $manager,SortieRepository $repository,int $idSortie):Response{
        $uneSortie = $repository->find($idSortie);
        if($uneSortie->getEtat()->getId()==2 && $uneSortie->getParticipantsInscrit()->count()<$uneSortie->getNbInscriptionMax() && $this->getUser()->getId() != $uneSortie->getOrganisateur()->getId()){
            $uneSortie->addParticipantsInscrit($this->getUser());
            $manager->flush();
            $this->addFlash('success','L\'inscription s\'est bien déroulé');
        }
        elseif ($uneSortie->getEtat()->getId()!=2){
            $this->addFlash('error','Erreur : vous ne pouvez pas vous inscrire, la sortie n\'est pas ouverte aux inscriptions');
        }
        elseif ($this->getUser()->getId()==$uneSortie->getOrganisateur()->getId()){
            $this->addFlash('error','Erreur : Vous êtes l\'organisateur de la sortie');
        }
        else{
            $this->addFlash('error','Erreur : le nombre maximum d\'inscrits a déjà été atteint');
        }
        return $this->redirectToRoute('app_home');

    }

    /**
     * @Route("/desister/{idSortie}",name="desister")
     */
    public function desister(Request $request,EntityManagerInterface $manager,SortieRepository $repository,int $idSortie):Response{
        $uneSortie = $repository->find($idSortie);
        $uneSortie->removeParticipantsInscrit($this->getUser());
        $manager->flush();
        $this->addFlash('success','Vous n\'etes plus inscrit à la sortie');
        return $this->redirectToRoute('app_home');

    }

    /**
     * @Route("/modifier/{idSortie}",name="update")
     */
    public function update(Request $request,EntityManagerInterface $manager,int $idSortie):Response{

        $uneSortie = $manager->getRepository(Sortie::class)->find($idSortie);

        if($uneSortie->getEtat()->getId()!=1){
            $this->addFlash('error','Erreur : Vous ne pouvez pas modifier cette sortie');
            return $this->redirectToRoute('app_home');

        }elseif($this->getUser()->getId() != $uneSortie->getOrganisateur()->getId()){
            $this->addFlash('error','Erreur :vous ne pouvez par modifier cette sortie car vous n\'etes pas l\'organisateur');
             return $this->redirectToRoute('app_home');
        }else{
            $repoVille = $manager->getRepository(Ville::class);
            $repoEtat = $manager->getRepository(Etat::class);
            $repoLieu = $manager->getRepository(Lieu::class);


            $sortieForm = $this->createForm(SortieFormType::class,$uneSortie);
            $sortieForm->handleRequest($request);


            if($sortieForm->isSubmitted()){
                //Changement de l'etat suivant le bouton qui a ete soumis
                if($request->get('submit')=="Enregistrer"){
                    $etat = $repoEtat->find(1);
                }elseif ( $request->get('submit')=="Supprimer la sortie"   ) {

                }else{

                    $etat = $repoEtat->find(2);
                }
                $uneSortie->setEtat($etat)
                    ->setLieu($repoLieu->find($request->get("lieu")))
                    ->setOrganisateur($this->getUser())
                    ->setSite($this->getUser()->getSite());
            }
            if($sortieForm->isSubmitted() && $sortieForm->isValid()){

                $manager->flush();
                $this->addFlash('success','Sortie modifiée');
                return $this->redirectToRoute('app_home');
            }

            return $this->render('sorties/modifier.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'villes'=>$repoVille->findAll(),
                'lieus'=>$uneSortie->getLieu()->getVille()->getLieus(),
                'lieuSortie'=>$uneSortie->getLieu(),
            ]);
        }
    }
    /**
     * @Route("/annuler/{idSortie}",name="annuler")
     */
    public function cancel(Request $request,EntityManagerInterface $manager, SortieRepository $sortieRepository,EtatRepository $etatRepository, int $idSortie):Response{


        $motifAnnule=$request->get('annuler_sortie');
        dump($request);
        $button=$request->get('button');
        if(isset($button)){

        }
        elseif(isset($motifAnnule)){


            if(empty($motifAnnule)){
                $this->addFlash('error', 'Vous devez entrer un motif');


            }
            else{
                $uneSortie = $sortieRepository->find($idSortie);
                $unEtat = $etatRepository->find(6);
                $uneSortie->setEtat($unEtat);
                $uneSortie->setMotif($motifAnnule);
                $manager->flush();
                $this->addFlash('success','Votre sortie a bien été annulée');
                return $this->redirectToRoute('app_home' );
            }

            }


        $sortie = $sortieRepository->find($idSortie);
        return $this->render('sorties/annuler.html.twig', compact('sortie'));
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
