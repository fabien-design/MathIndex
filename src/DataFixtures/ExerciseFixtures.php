<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ExerciseFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_IDENTIFIER = 'exercise_';

    public const EXERCISES = [
        [
            'name' => 'Factorisation polynomiale',
            'course' => 'mathématique-course',
            'classroom' => 'seconde-class',
            'thematic' => 'calcul-intégral-maths',
            'chapter' => 'Chapitre 2',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 3,
            'duration' => 45.5,
            'origin' => 'manuel-scolaire-origin',
            'originName' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'exerciseFile' => 'original-file1.pdf-file',
            'correctionFile' => 'original-file2.docx-file',
            'createdBy' => 'guyard-mathTeacher',
        ],
        [
            'name' => "Dérivation d'une fonction exponentielle",
            'course' => 'mathématique-course',
            'classroom' => 'première-class',
            'thematic' => 'calcul-intégral-maths',
            'chapter' => 'Chapitre 3',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 4,
            'duration' => 200,
            'origin' => 'manuel-scolaire-origin',
            'originName' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'exerciseFile' => 'original-file3.pdf-file',
            'correctionFile' => 'original-file4.pdf-file',
            'createdBy' => 'guyard-mathTeacher',
        ],
        [
            'name' => 'Coordonnées',
            'course' => 'mathématique-course',
            'classroom' => '3ème-class',
            'thematic' => 'géométrie-euclidienne-maths',
            'chapter' => 'Chapitre 5',
            'keywords' => 'algèbre@maths@calcul',
            'difficulty' => 2,
            'duration' => 150,
            'origin' => 'manuel-scolaire-origin',
            'originName' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'exerciseFile' => 'original-file5.pdf-file',
            'correctionFile' => 'original-file6.pdf-file',
            'createdBy' => 'guyard-mathTeacher',
        ],
        [
            'name' => 'Molière, le malade imaginaire',
            'course' => 'francais-course',
            'classroom' => 'seconde-class',
            'thematic' => 'théatre-francais',
            'chapter' => 'Chapitre 5',
            'keywords' => 'théatre@molière',
            'difficulty' => 2,
            'duration' => 150,
            'origin' => 'livre-origin',
            'originName' => 'Le Malade Imaginaire',
            'originInformation' => 'Livre de molière',
            'proposedByType' => 'Livre',
            'exerciseFile' => 'original-file7.pdf-file',
            'correctionFile' => 'original-file8.pdf-file',
            'createdBy' => 'hougron-frTeacher',
        ],
        [
            'name' => 'Paris Ville Lumière',
            'course' => 'francais-course',
            'classroom' => 'bts-1-class',
            'thematic' => 'culture-générale-francais',
            'chapter' => 'Chapitre 2',
            'keywords' => 'paris@littérature@arts',
            'difficulty' => 2,
            'duration' => 90,
            'origin' => 'livre-origin',
            'origin_name' => 'classique&cie BTS',
            'origin_information' => 'Johan Faerber',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Virginie',
            'proposedByLasName' => 'Hougron',
            'exerciseFile' => 'original-file9.pdf-file',
            'correctionFile' => 'original-file10.pdf-file',
            'createdBy' => 'hougron-frTeacher',
        ],
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::EXERCISES as $i => $exerciseInfo) {
            $exercise = (new Exercise())
                ->setName($exerciseInfo['name'])
                ->setCourse($this->getReference($exerciseInfo['course']))
                ->setClassroom($this->getReference($exerciseInfo['classroom']))
                ->setThematic($this->getReference($exerciseInfo['thematic']))
                ->setChapter($exerciseInfo['chapter'])
                ->setKeywords($exerciseInfo['keywords'])
                ->setDifficulty(3)
                ->setDuration(45.5)
                ->setOrigin($this->getReference($exerciseInfo['origin']))
                ->setOriginName($exerciseInfo['origin_name'])
                ->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".')
                ->setProposedByType('Enseignant')
                ->setProposedByFirstName('Laurent')
                ->setProposedByLasName('Guyard')
                ->setExerciseFile($this->getReference('original-file1.pdf-file'))
                ->setCorrectionFile($this->getReference('original-file2.docx-file'))
                ->setCreatedBy($this->getReference('guyard-mathTeacher'))
                ->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

            $manager->persist($exercise);
            $this->addReference(self::REFERENCE_IDENTIFIER.$i, $exercise);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ThematicFixtures::class,
            UserFixtures::class,
            OriginFixtures::class,
            FileFixtures::class,
            CourseFixtures::class,
            ClassroomFixtures::class,
        ];
    }
}
