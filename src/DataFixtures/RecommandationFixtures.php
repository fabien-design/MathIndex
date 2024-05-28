<?php 

namespace App\DataFixtures;

use App\Entity\Recommandation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RecommandationFixtures extends Fixture
{
    public const REFERENCE_IDENTIFIER = 'recommandation_';

    public const RECOMMANDATIONS = [
        'Très Faible',
        'Faible',
        'Elevé',
        'Très Elevé',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::RECOMMANDATIONS as $i => $rec) {
            $recommandation = (new Recommandation())
                ->setName($rec);

            $manager->persist($recommandation);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $recommandation);
        }

        $manager->flush();
    }
}
