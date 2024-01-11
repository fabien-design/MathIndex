<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Origin;

class OriginFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Origins
        $origins = ['Livre', 'Site Web', 'Manuel scolaire'];

        foreach ($origins as $originName) {
            $origin = new Origin();
            $origin->setName($originName);
            $manager->persist($origin);

            // You can add a reference to this origin if needed
            $this->addReference(strtolower(str_replace(' ', '-', $originName)) . '-origin', $origin);
        }

        $manager->flush();
    }
}
