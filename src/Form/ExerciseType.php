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
            ->add('createdAt')
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'id',
            ])
            ->add('classroom', EntityType::class, [
                'class' => Classroom::class,
                'choice_label' => 'id',
            ])
            ->add('thematic', EntityType::class, [
                'class' => Thematic::class,
                'choice_label' => 'id',
            ])
            ->add('origin', EntityType::class, [
                'class' => Origin::class,
                'choice_label' => 'id',
            ])
            ->add('exerciseFile', EntityType::class, [
                'class' => File::class,
                'choice_label' => 'id',
            ])
            ->add('correctionFile', EntityType::class, [
                'class' => File::class,
                'choice_label' => 'id',
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
