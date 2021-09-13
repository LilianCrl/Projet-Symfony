<?php

namespace App\Controller;


use App\Entity\Participants;
use App\Form\UpdateProfileType;
use App\Repository\ParticipantsRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Gedmo\Sluggable\Util\Urlizer;

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
    public function updateProfile(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, Security $security, UploaderHelper $uploaderHelper): Response
    {
        $this->security = $security;
        // Get the current connected user in order to update whatever he wants
        $participant = $this->security->getToken()->getUser();
        $form = $this->createForm(UpdateProfileType::class, $participant);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid()){
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form['maPhotoFileName']->getData();
            if( $uploadedFile){
                $newFilename = $uploaderHelper->uploadParticipantImage($uploadedFile);
                $participant->setMaPhoto($newFilename);
            }
            $participant->setPassword(
                $passwordHasher->hashPassword(
                    $participant,
                    $form->get('Password')->getData()
                )
            );
            $em->flush();

            return $this->render('participant/afficherProfil.html.twig', compact('participant'));
        }
        return $this->render('participant/monProfil.html.twig', [
            'UpdateProfile'=> $form->createView(),
            ]);
    }

    /**
     * @Route("/afficherProfil/{pseudo}", name="afficher_profil")
     */
    public function showProfile(ParticipantsRepository $participantsRepository,$pseudo): Response
    {
        $participant = $participantsRepository->findOneBy(["pseudo"=>$pseudo]);
        return $this->render('participant/afficherProfil.html.twig', compact('participant'));
    }
}
