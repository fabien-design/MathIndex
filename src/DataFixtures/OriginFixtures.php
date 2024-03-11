<?php

namespace App\DataFixtures;

use App\Entity\Origin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class OriginFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'origin_';

    public const ORIGINS = [
        [
            'Livre',
            'Site Web',
            'Manuel scolaire',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::ORIGINS as $i => $originName) {
            $origin = (new Origin())
                ->setName($originName[$i]);

            $manager->persist($origin);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $origin);
        }

        $manager->flush();
    }
}
