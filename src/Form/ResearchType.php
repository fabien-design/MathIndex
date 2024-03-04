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
            ->add('keywords', TextType::class, [
                    'label' => 'Mots-clés',
                    'required' => false,
                    'attr' => [
                    'class' => 'flex items-center justify-center rounded-lg w-full h-[56px] sm:min-w-[234px] ',
                    ],
                ]
            )
            ->add('thematics', EntityType::class, [
                'class' => Thematic::class,
                'choice_label' => 'name',
                'label' => 'Thématiques',
                'placeholder' => '---',
                'required' => false,
                'attr' => [
                    'class' => 'flex items-center justify-center rounded-lg w-full h-[56px] sm:min-w-[234px] ',
                ],
            ])
            ->add('levels', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'name',
                'label' => 'Niveaux',
                'placeholder' => '---',
                'required' => false,
                'attr' => [
                        'class' => 'flex items-center justify-center rounded-lg w-full h-[56px] sm:min-w-[234px]',
                    ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => [
                'class' => 'flex items-center justify-center px-8 bg-gray-100 rounded-lg w-full h-[56px] text-[#757575] hover:bg-gray-200 transition-all hover:text-[#4D4D4D]',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
            'attr' => ['class' => 'researchForm flex justify-between items-end flex-wrap w-11/12'],
        ]);
    }
}
