<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'user_';

    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public const USERS = [
        [
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'ROLE_ADMIN',
        ],
        [
            'firstname' => 'Laurent',
            'lastname' => 'Guyard',
            'email' => 'mathteacher@example.com',
            'role' => 'ROLE_TEACHER',
        ],
        [
            'firstname' => 'Virginie',
            'lastname' => 'Hougron',
            'email' => 'frteacher@example.com',
            'role' => 'ROLE_TEACHER',
        ],
        [
            'firstname' => 'Jane',
            'lastname' => 'Smith',
            'email' => 'student@example.com',
            'role' => '',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::USERS as $i => $appUser) {
            $user = new User();
            $user->setFirstname($appUser['firstname'])
                ->setLastname($appUser['lastname'])
                ->setEmail($appUser['email'])
                ->setRoles([$appUser['role']])
                ->setPassword($this->passwordHasher->hashPassword($user, 'xxx'));

            $manager->persist($user);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $user);
        }

        $manager->flush();
    }
}
