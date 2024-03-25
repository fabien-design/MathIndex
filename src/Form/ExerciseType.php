<?php

namespace App\Form;

use App\Entity\Classroom;
use App\Entity\Course;
use App\Entity\Exercise;
use App\Entity\File;
use App\Entity\Origin;
use App\Entity\Thematic;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('chapter')
            ->add('keywords')
            ->add('difficulty')
            ->add('duration')
            ->add('originName')
            ->add('originInformation')
            ->add('proposedByType')
            ->add('proposedByFirstName')
            ->add('proposedByLasName')
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
            ->add('exerciseFile', FileType::class, [
                'data_class' => File::class,
             ])
            ->add('correctionFile', FileType::class, [
                'data_class' => File::class,
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
        ]);
    }
}
