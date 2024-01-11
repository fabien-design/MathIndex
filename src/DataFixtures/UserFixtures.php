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
        $mathTeacher = new User();
        $mathTeacher->setEmail('teacher@example.com');
        $mathTeacher->setFirstname('Laurent');
        $mathTeacher->setLastname('Guyard');
        $mathTeacher->setRoles(['ROLE_TEACHER']);
        $mathTeacher->setPassword($this->passwordHasher->hashPassword($mathTeacher, 'teacher'));

        $frTeacher = new User();
        $frTeacher->setEmail('frteacher@example.com');
        $frTeacher->setFirstname('Virginie');
        $frTeacher->setLastname('Hougron');
        $frTeacher->setRoles(['ROLE_TEACHER']);
        $frTeacher->setPassword($this->passwordHasher->hashPassword($frTeacher, 'frteacher'));

        // Persist the users
        $manager->persist($admin);
        $this->addReference(strtolower(str_replace(' ', '-', $admin->GetLastname())).'-admin', $admin);

        $manager->persist($student);
        $this->addReference(strtolower(str_replace(' ', '-', $student->GetLastname())).'-student', $student);

        $manager->persist($mathTeacher);
        $this->addReference(strtolower(str_replace(' ', '-', $mathTeacher->GetLastname())).'-mathTeacher', $mathTeacher);

        $manager->persist($frTeacher);
        $this->addReference(strtolower(str_replace(' ', '-', $frTeacher->GetLastname())).'-frTeacher', $frTeacher);

        $manager->flush();
    }
}