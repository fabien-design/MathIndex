<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
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
                'invalid_message' => 'Les mots de pass ne correspondent pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'mapped' => false,
                'first_options' => [
                    'hash_property_path' => 'password',
                    'label' => 'Nouveau mot de passe',
                    'attr' => ['autocomplete' => 'new-password', 'class' => 'user_password_first'], // Disable autocomplete for first password field
                ],
                'second_options' => [
                    'label' => 'Répéter le mot de passe',
                    'attr' => ['autocomplete' => 'new-password', 'class' => 'user_password_second'], // Disable autocomplete for second password field
                ],
            ]);

        // Add roles field only if the user doesn't have ROLE_ADMIN
        if (!in_array('ROLE_ADMIN', $options['user_roles'])) {
            $builder->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'Étudiant' => 'ROLE_STUDENT',
                    'Enseignant' => 'ROLE_TEACHER',
                ],
            ]);

            $builder->get('roles')
                ->addModelTransformer(new CallbackTransformer(
                    function ($rolesArray) {
                        // transform the array to a string
                        return count($rolesArray) ? $rolesArray[0] : null;
                    },
                    function ($rolesString) {
                        // transform the string back to an array
                        return [$rolesString];
                    }
                ));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user_roles' => [], // Additional option to pass user roles
        ]);
    }
}
