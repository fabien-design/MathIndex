<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'course_';

    public const COURSES = [
        'Francais',
        'Mathématique',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::COURSES as $i => $courseName) {
            $course = (new Course())
                ->setName($courseName);

            $manager->persist($course);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $course);
        }

        $manager->flush();
    }
}
