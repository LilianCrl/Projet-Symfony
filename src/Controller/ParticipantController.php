<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\UpdateProfileType;
use App\Repository\ParticipantsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/participant", name="app_participant_")
 */
class ParticipantController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/monProfil", name="mon_profil")
     */
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Security $security): Response
    {
        $this->security = $security;
        // Get the current connected user in order to update whatever he wants
        $participant = $this->security->getToken()->getUser();
        $form = $this->createForm(UpdateProfileType::class, $participant);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            //dd($form['maPhoto']->getData());
            $participant->setPassword(
                $passwordHasher->hashPassword(
                    $participant,
                    $form->get('Password')->getData()
                )
            );
            $em->flush();

            return $this->redirectToRoute('app_participant_afficher_profil');
        }
        return $this->render('participant/monProfil.html.twig', [
            'UpdateProfile'=> $form->createView(),
            ]);
    }

    /**
     * @Route("/afficherProfil", name="afficher_profil")
     */
    public function showProfile(): Response
    {
        return $this->render('participant/afficherProfil.html.twig');
    }
}
