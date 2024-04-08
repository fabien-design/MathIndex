<?php

namespace App\DataFixtures;

use App\Entity\Classroom;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClassroomFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'class_';

    public const CLASSROOMS = [
        '6ème',
        '5ème',
        '4ème',
        '3ème',
        'Seconde',
        'Première',
        'Terminale',
        'BTS 1',
        'BTS 2',
        'License',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CLASSROOMS as $i => $class) {
            $classroom = (new Classroom())
                ->setName($class);

            $manager->persist($classroom);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $classroom);
        }

        $manager->flush();
    }
}
