<?php

namespace App\Form;

use App\Entity\Chequier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChequierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_creation')
            ->add('motif_chequier')
            ->add('id_chequier')
         //   ->add('Ajouter',SubmitType::class)

            //  ->add('num_compte')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chequier::class,
        ]);
    }
}
