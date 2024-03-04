<?php

// src/DataFixtures/ThematicFixtures.php

namespace App\DataFixtures;

use App\Entity\Thematic;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ThematicFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'thematic_';

    public const THEMATICS = [
        ['thematic' => 'Theâtre', 'course' => 0],
        ['thematic' => 'Culture Générale', 'course' => 0],
        ['thematic' => 'Grammaire avancée', 'course' => 0],
        ['thematic' => 'Algèbre linaire', 'course' => 1],
        ['thematic' => 'Calcul intégral', 'course' => 1],
        ['thematic' => 'Géométrie euclidienne', 'course' => 1],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::THEMATICS as $i => $thematicInfo) {
            $thematic = (new Thematic())
                ->setName($thematicInfo['thematic'])
                ->setCourse($this->getReference(CourseFixtures::REFERENCE_IDENTIFIER.$thematicInfo['course']));

            $manager->persist($thematic);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $thematic);
        }

        $manager->flush();
    }
}
