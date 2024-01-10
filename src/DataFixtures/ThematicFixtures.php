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
        $francaisCourse = $this->getReference('français-course');
        $mathematiqueCourse = $this->getReference('mathématique-course');

        // Thematics for 'Français'
        $francaisThematics = ['Poésie', 'Roman', 'Grammaire avancée'];

        foreach ($francaisThematics as $thematicName) {
            $francaisThematic = new Thematic();
            $francaisThematic->setName($thematicName);
            $francaisThematic->setCourse($francaisCourse);
            $manager->persist($francaisThematic);
        }

        // Thematics for 'Mathématique'
        $mathematiqueThematics = ['Algèbre linéaire', 'Calcul intégral', 'Géométrie euclidienne'];

        foreach ($mathematiqueThematics as $thematicName) {
            $mathematiqueThematic = new Thematic();
            $mathematiqueThematic->setName($thematicName);
            $mathematiqueThematic->setCourse($mathematiqueCourse);
            $manager->persist($mathematiqueThematic);
        }

        $manager->flush();
    }
}
