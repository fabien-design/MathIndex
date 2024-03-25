<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\File;
use App\Entity\Origin;
use App\Entity\Thematic;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('chapter', TextType::class)
            ->add('keywords', TextType::class)
            ->add('difficulty', IntegerType::class)
            ->add('duration', NumberType::class)
            ->add('originName', TextType::class)
            ->add('originInformation', TextType::class)
            ->add('proposedByType', TextType::class)
            ->add('proposedByFirstName', TextType::class)
            ->add('proposedByLasName', TextType::class)
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'name',
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'name',
            ])
            ->add('thematic', EntityType::class, [
                'class' => Thematic::class,
                'choice_label' => 'name',
            ])
            ->add('origin', EntityType::class, [
                'class' => Origin::class,
                'choice_label' => 'name',
            ])
            ->add('enonceFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => '.pdf, application/pdf, .doc, .docx, .odt'
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword',       // MIME type for .doc files
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // MIME type for .docx files
                            'application/vnd.oasis.opendocument.text', // MIME type for .odt files
                        ],
                    ]),
                ]
            ])
            
            ->add('correctFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'accept' => '.pdf, application/pdf, .doc, .docx, .odt'
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\File([
                        'mimeTypes' => [
                            'application/pdf',
                            'application/msword',       // MIME type for .doc files
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // MIME type for .docx files
                            'application/vnd.oasis.opendocument.text', // MIME type for .odt files
                        ],
                    ]),
                ]
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
        ]);
    }
}
