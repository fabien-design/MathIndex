<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('lastname', TextType::class, ['label' => 'Nom'])
        ->add('firstname', TextType::class, ['label' => 'Prénom'])
        ->add('email')
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'The password fields must match.',
            'options' => ['attr' => ['class' => 'password-field']],
            'required' => false,
            'mapped' => false,
            'first_options'  => ['label' => 'Nouveau mot de passe'],
            'second_options' => ['label' => 'Répéter le mot de passe'],
        ])
        // ->add('roles', ChoiceType::class, [
        //     'label'    => 'Roles',
        //     'required' => false,
        //     'choices'  => [
        //         'Étudiant ?' => 'ROLE_USER',
        //         'Contributeur ?' => 'ROLE_CONTRIBUTOR',
        //         'Administrateur ?' => 'ROLE_ADMIN',
        //         // Add more roles here as needed
        //     ],
        //     'multiple' => true,
        //     // 'expanded' => true, // This will display checkboxes instead of a select dropdown
        // ])
        ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
