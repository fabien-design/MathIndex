<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        // Create admin user
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Super');
        $admin->setLastname('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin'));

        // Create student user
        $student = new User();
        $student->setEmail('student@example.com');
        $student->setFirstname('Lucas');
        $student->setLastname('Dupas');
        $student->setRoles(['ROLE_STUDENT']);
        $student->setPassword($this->passwordHasher->hashPassword($student, 'student'));

        // Create teacher user
        $teacher = new User();
        $teacher->setEmail('teacher@example.com');
        $teacher->setFirstname('Laurent');
        $teacher->setLastname('Guyard');
        $teacher->setRoles(['ROLE_TEACHER']);
        $teacher->setPassword($this->passwordHasher->hashPassword($teacher, 'teacher'));

        // Persist the users
        $manager->persist($admin);
        $manager->persist($student);
        $manager->persist($teacher);

        $manager->flush();
    }
}