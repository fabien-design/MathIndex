<?php

// src/DataFixtures/SkillFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Skill;
use App\Entity\Course;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Get references to the courses
        $francaisCourse = $this->getReference('français-course');
        $mathematiqueCourse = $this->getReference('mathématique-course');

        // Skills for 'Français'
        $francaisSkills = ['Lecture', 'Écriture', 'Grammaire'];

        foreach ($francaisSkills as $skillName) {
            $francaisSkill = new Skill();
            $francaisSkill->setName($skillName);
            $francaisSkill->addCourse($francaisCourse);
            $manager->persist($francaisSkill);
        }

        // Skills for 'Mathématique'
        $mathematiqueSkills = ['Algèbre', 'Géométrie', 'Statistiques'];

        foreach ($mathematiqueSkills as $skillName) {
            $mathematiqueSkill = new Skill();
            $mathematiqueSkill->setName($skillName);
            $mathematiqueSkill->addCourse($mathematiqueCourse);
            $manager->persist($mathematiqueSkill);
        }

        $manager->flush();
    }
}
