<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Exercise;
use App\Entity\Thematic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keywords',TextType::class, [
                    'label' => 'Mots-clés',
                    'attr' => [
                        'class' => 'flex items-center justify-center'
                    ],
                ]
            )
            ->add('thematics', EntityType::class, [
                'class' => Thematic::class,
                'choice_label' => 'name',
                'label' => 'Thématiques',
                'attr' => [
                    'class' => 'flex items-center justify-center'
                ],
            ])
            ->add('levels', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'name',
                'label' => 'Niveaux',
                'attr' => [
                        'class' => 'flex items-center justify-center'
                    ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                    'class' => 'flex items-center justify-center p-2 bg-gray-100'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
            'attr' => ['class' => 'researchForm flex justify-evenly items-center flex-wrap w-4/4'],
        ]);
    }
}
