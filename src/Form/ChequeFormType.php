<?php

namespace App\Form;

use App\Entity\Cheques;
use App\Entity\Chequier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('date_cheque')
            ->add('lieu')
            ->add('signature')
            ->add('destinataire')
            ->add('Carnets_cheques', EntityType::class, ['class' => Chequier::class, 'choice_label'=> function($chequier){
                return $chequier->getId();}])
           // ->add('Valider',SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cheques::class,
        ]);
    }



}
