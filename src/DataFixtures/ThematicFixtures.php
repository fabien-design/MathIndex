<?php

// src/DataFixtures/ThematicFixtures.php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Thematic;
use App\Entity\Course;

class ThematicFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Get references to the courses
        $francaisCourse = $this->getReference('francais-course');
        $mathematiqueCourse = $this->getReference('mathématique-course');

        // Thematics for 'Français'
        $francaisThematics = ['Théatre', 'Culture générale', 'Grammaire avancée'];

        foreach ($francaisThematics as $thematicName) {
            $francaisThematic = new Thematic();
            $francaisThematic->setName($thematicName);
            $francaisThematic->setCourse($francaisCourse);
            $manager->persist($francaisThematic);
            $this->addReference(strtolower(str_replace(' ', '-', $thematicName)).'-francais', $francaisThematic);
        }

        // Thematics for 'Mathématique'
        $mathematiqueThematics = ['Algèbre linaire', 'Calcul intégral', 'Géométrie euclidienne'];

        foreach ($mathematiqueThematics as $thematicName) {
            $mathematiqueThematic = new Thematic();
            $mathematiqueThematic->setName($thematicName);
            $mathematiqueThematic->setCourse($mathematiqueCourse);
            $manager->persist($mathematiqueThematic);
            $this->addReference(strtolower(str_replace(' ', '-', $thematicName)).'-maths', $mathematiqueThematic);
        }

        $manager->flush();
    }
}
