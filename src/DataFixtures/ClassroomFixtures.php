<?php

// src/DataFixtures/ClassroomFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Classroom;

class ClassroomFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Classes de la 6ème à la BTS 2
        $classrooms = [
            '6ème', '5ème', '4ème', '3ème',
            'Seconde', 'Première', 'Terminale',
            'BTS 1', 'BTS 2', 'License',
        ];

        foreach ($classrooms as $className) {
            $classroom = new Classroom();
            $classroom->setName($className);
            $manager->persist($classroom);
        }

        $manager->flush();
    }
}
