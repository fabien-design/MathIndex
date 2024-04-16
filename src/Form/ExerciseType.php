<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\Origin;
use App\Entity\Skill;
use App\Entity\Thematic;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileValid;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // Tab 1
        ->add('name', TextType::class, [
            'label' => 'Nom de l\'exercice * :',
            'constraints' => [
                new NotBlank(['groups' => ['new', 'edit'], 'message' => 'Le nom est obligatoire.']),
            ],
            ])
        ->add('course', EntityType::class, [
            'label' => 'Matière * :',
            'class' => Course::class,
            'choice_label' => 'name',
            'constraints' => [
                new NotBlank(['groups' => ['new', 'edit'], 'message' => 'La matière est obligatoire.']),
            ],
        ])
        ->add('classroom', EntityType::class, [
            'label' => 'Classe * :',
            'class' => Classroom::class,
            'choice_label' => 'name',
            'constraints' => [
                new NotBlank(['groups' => ['new', 'edit'], 'message' => 'La classe est obligatoire.']),
            ],
        ])
        ->add('thematic', EntityType::class, [
            'label' => 'Thématique * :',
            'class' => Thematic::class,
            'choice_label' => 'name',
            'constraints' => [
                new NotBlank(['groups' => ['new', 'edit'], 'message' => 'La thématique est obligatoire.']),
            ],
        ])
        ->add('skills', EntityType::class, [
            'class' => Skill::class,
            'label' => 'Compétences :',
            'multiple' => true,
            'expanded' => true,
            'choice_label' => 'name',
        ])
        ->add('chapter', TextType::class, [
            'label' => 'Chapitre du cours :',
            ])
        ->add('keywords', HiddenType::class, [
            'label' => 'Les vrais mots clés :',
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
            'constraints' => [
                new NotBlank(['groups' => ['new', 'edit'], 'message' => 'La difficulté est obligatoire.']),
            ],
        ])

        ->add('duration', NumberType::class, [
            'label' => 'Durée (en heure) :',
            'html5' => true,
        ])
        // Tab 2
        ->add('origin', EntityType::class, [
            'label' => 'Origine :',
            'class' => Origin::class,
            'choice_label' => 'name',
            'placeholder' => 'Choisir une origine',
        ])
        ->add('originName', TextType::class, [
            'label' => 'Nom du livre/lien du site :',
        ])
        ->add('originInformation', TextareaType::class, [
            'label' => 'Informations complémentaires :',
        ])
        ->add('proposedByType', ChoiceType::class, [
            'label' => 'Proposé par un :',
            'choices' => [
                'Enseignant' => 'Enseignant',
                'Étudiant' => 'Étudiant',
            ],
            'placeholder' => 'Choisir une option',
        ])
        ->add('proposedByLasName', TextType::class, [
            'label' => 'Nom :',
        ])
        ->add('proposedByFirstName', TextType::class, [
            'label' => 'Prénom :',
        ])
        // Tab 3
        ->add('enonceFile', FileType::class, [
            'label' => 'Fiche exercice (PDF, word) * :',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['new'], 'message' => 'Le fichier du sujet est obligatoire.']),
                new FileValid([
                    'extensions' => [
                        'pdf',
                        'docx',
                        'doc',
                        'odt',
                        'odp',
                    ],
                    'groups' => ['new'],
                    'extensionsMessage' => 'Veuillez téléverser un fichier PDF, DOCX, DOC, ODT ou ODP valide.',
                ]),
            ],
            'attr' => [
                'accept' => '.pdf,.doc,.docx,.odt,.odp',
            ],
        ])
        ->add('correctFile', FileType::class, [
            'label' => 'Fiche corrigé (PDF, word) * :',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['new'], 'message' => 'Le fichier de correction est obligatoire.']),
                new FileValid([
                    'extensions' => [
                        'pdf',
                        'docx',
                        'doc',
                        'odt',
                        'odp',
                    ],
                    'groups' => ['new'],
                    'extensionsMessage' => 'Veuillez téléverser un fichier PDF, DOCX, DOC, ODT ou ODP valide.',
                ]),
            ],
            'attr' => [
                'accept' => '.pdf,.doc,.docx,.odt,.odp',
            ],
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
            'constraints' => [
                new Callback([$this, 'validateOriginOrProposedBy'], ['new', "edit"])
            ]
        ]);
    }
    

    public function validateOriginOrProposedBy($data, ExecutionContextInterface $context)
    {
        $origin = $data->getOrigin();

        $originName = $data->getOriginName();
        $originInformation = $data->getOriginInformation();

        $proposedBy = $data->getProposedByType();

        $proposedByLastName = $data->getProposedByLasName();
        $proposedByFirstName = $data->getProposedByFirstName();

        if (empty($origin) && empty($proposedBy)) {
            $context->buildViolation('Vous devez renseigner une origine')
                ->atPath('origin')
                ->addViolation();

            $context->buildViolation('ou un contributeur.')
            ->atPath('proposedByType')
            ->addViolation();
        }

        if (!empty($origin) &&!empty($proposedBy)) {
            $context->buildViolation('Vous devez renseigner soit une origine')
                ->atPath('origin')
                ->addViolation();
                $context->buildViolation('soit un contributeur.')
                ->atPath('proposedByType')
                ->addViolation();
        }

        if (!empty($origin && empty($proposedBy))) {
            if (empty($originName) || empty($originInformation)) {
                $context->buildViolation('Vous devez renseigner le nom et les informations complémentaires de l\'origine.')
                    ->atPath('origin')
                    ->addViolation();
            }
            if (!empty($proposedByLastName) || !empty($proposedByFirstName)) {
                $context->buildViolation('Vous ne devez pas renseigner ces champs.')
                    ->atPath('proposedByLasName')
                    ->addViolation();
                $context->buildViolation('Vous ne devez pas renseigner ces champs.')
                ->atPath('proposedByFirstName')
                ->addViolation();
            }
        }

        if (!empty($proposedBy && empty($origin))) {
            if (empty($proposedByLastName)) {
                $context->buildViolation('Vous devez renseigner le nom du contributeur.')
                    ->atPath('proposedByLasName')
                    ->addViolation();
            }
            if(empty($proposedByFirstName))
            {
                $context->buildViolation('Vous devez renseigner le prénom du contributeur.')
                ->atPath('proposedByFirstName')
                ->addViolation();
            }
            if (!empty($originName) ||!empty($originInformation)) {
                $context->buildViolation('Vous ne devez pas renseigner ces champs.')
                    ->atPath('originName')
                    ->addViolation();
                $context->buildViolation('Vous ne devez pas renseigner ces champs.')
                ->atPath('originInformation')
                ->addViolation();
            }
        }
    } 
}
