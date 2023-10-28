<?php

namespace App\Form;
use App\Controller\SubmitType;
use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre_publication')
            ->add('Description_publication')
            ->add('categorie_publication', ChoiceType::class,
            [
                'choices' => [
                    "Crypto-monnaie" => "crypto-monnaie",
                    "Devises" => "devises"
                ],
                'expanded' =>false,
            ])
            ->add('image_publication',FileType::class, array('data_class' => null,'required' => false))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
