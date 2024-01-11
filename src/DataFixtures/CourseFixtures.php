<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Course;

class CourseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Courses 'Français' and 'Mathématique'
        $courseNames = ['Francais', 'Mathématique'];

        foreach ($courseNames as $courseName) {
            $course = new Course();
            $course->setName($courseName);
            $manager->persist($course);
            $this->addReference(strtolower(str_replace(' ', '-', $courseName)).'-course', $course);
        }

        $manager->flush();
    }
}
