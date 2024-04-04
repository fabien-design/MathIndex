<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Thematic;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom de l\'exercice * :',
            ])
        ->add('course', EntityType::class, [
            'label' => 'Matière * :',
            'class' => Course::class,
            'choice_label' => 'name',
        ])
        ->add('classroom', EntityType::class, [
            'label' => 'Classe * :',
            'class' => Classroom::class,
            'choice_label' => 'name',
        ])
        ->add('thematic', EntityType::class, [
            'label' => 'Thématique * :',
            'class' => Thematic::class,
            'choice_label' => 'name',
        ])
        ->add('chapter', TextType::class, [
            'label' => 'Chapitre du cours :',
            ])
        ->add('keywords', HiddenType::class, [
            'label' => 'Les vrais ots clés :',
            'attr' => ['class' => 'hidden realExerciseKeywords'],
            'required' => false,
            ])
        ->add('fakeKeywords', TextType::class, [
                'label' => 'Mots clés :',
                'mapped' => false,
                'attr' => ['class' => 'exerciseKeywords'],
                'required' => false,
                ])
        ->add('difficulty', ChoiceType::class, [
            'label' => 'Difficulté * :',
            'choices' => [
                'Niveau 1' => 1,
                'Niveau 2' => 2,
                'Niveau 3' => 3,
                'Niveau 4' => 4,
                'Niveau 5' => 5,
                'Niveau 6' => 6,
                'Niveau 7' => 7,
                'Niveau 8' => 8,
                'Niveau 9' => 9,
                'Niveau 10' => 10,
                'Niveau 11' => 11,
                'Niveau 12' => 12,
                'Niveau 13' => 13,
                'Niveau 14' => 14,
                'Niveau 15' => 15,
                'Niveau 16' => 16,
                'Niveau 17' => 17,
                'Niveau 18' => 18,
                'Niveau 19' => 19,
                'Niveau 20' => 20,
            ],
        ])

        ->add('duration', NumberType::class, [
            'label' => 'Durée (en heure) :',
            'html5' => true,
        ])
        ->add('originName', TextType::class, [
            'label' => 'Provenance de l\'exercice :',
        ])
        ->add('originInformation', TextType::class, [
            'label' => 'Nom de l\'exercice :',
        ])
        ->add('proposedByType', TextType::class, [
            'label' => 'Nom de l\'exercice :',
        ])
        ->add('proposedByFirstName', TextType::class, [
            'label' => 'Nom de l\'exercice :',
        ])
        ->add('proposedByLasName', TextType::class, [
            'label' => 'Nom de l\'exercice :',
        ])
        ->add('origin', EntityType::class, [
            'class' => Origin::class,
            'choice_label' => 'name',
        ])
        ->add('enonceFile', FileType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['new'], 'message' => 'Le fichier du sujet est obligatoire.']),
                new \Symfony\Component\Validator\Constraints\File([
                    'mimeTypes' => [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.oasis.opendocument.text',
                    ],
                    'groups' => ['new'],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, DOCX ou ODT valide.',
                ]),
            ],
            'attr' => [
                'accept' => '.pdf,.doc,.docx,.odt',
            ],
        ])
        ->add('correctFile', FileType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['new'], 'message' => 'Le fichier de correction est obligatoire.']),
                new \Symfony\Component\Validator\Constraints\File([
                    'mimeTypes' => [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.oasis.opendocument.text',
                    ],
                    'groups' => ['new'],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, DOCX ou ODT valide.',
                ]),
            ],
            'attr' => [
                'accept' => '.pdf,.doc,.docx,.odt',
            ],
        ])
        ->add('createdBy', EntityType::class, [
            'class' => User::class,
            'choice_label' => 'id',
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exercise::class,
            'allow_extra_fields' => true,
            'validation_groups' => ['new', 'edit'],
            'attr' => ['id' => 'exerciceForm'],
        ]);
    }
}
