<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participants;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateTimeType::class,[
                'widget'=>'single_text',
            ])
            ->add('duree')
            ->add('dateLimiteInscription',DateTimeType::class,[
                'widget'=>'single_text',
            ])
            ->add('nbInscriptionMax')
            ->add('infosSortie')
            ->add('lieu',entityType::class,['class'=>Lieu::class,'choice_label'=>'nom'])
            ->add('etat',entityType::class,['class'=>Etat::class,'choice_label'=>'libelle'])
            ->add('site',entityType::class,['class'=>Site::class,'choice_label'=>'nom'])
            ->add('organisateur',entityType::class,['class'=>Participants::class,'choice_label'=>'pseudo'])
           // ->add('participantsInscrit')
            ->add("Publier",SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
