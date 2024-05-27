<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;

class ExerciseFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_IDENTIFIER = 'exercise_';

    public const EXERCISES = [
        [
            'name' => 'Factorisation polynomiale',
            'course' => 'course_1',
            'thematic' => 'thematic_3',
            'chapter' => 'Chapitre 2',
            'keywords' => '@algèbre@maths@calcul',
            'difficulty' => 3,
            'duration' => 1.25,
            'original_name' => 'Mathématiques avancées',
            'originInformation' => 'Exercice tiré du livre "Mathématiques avancées".',
            'proposedByType' => '',
            'proposedByFirstName' => '',
            'proposedByLasName' => '',
            'file' => 'file_0',
            'correction_file' => 'file_5',
            'created_by' => 'user_1',
        ],
        [
            'name' => "Dérivation d'une fonction exponentielle",
            'course' => 'course_1',
            'chapter' => 'Chapitre 3',
            'keywords' => '@algèbre@maths@calcul',
            'thematic' => 'thematic_4',
            'difficulty' => 4,
            'duration' => 0.20,
            'original_name' => '',
            'originInformation' => '',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'file' => 'file_4',
            'correction_file' => 'file_6',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Coordonnées',
            'course' => 'course_1',
            'chapter' => 'Chapitre 5',
            'keywords' => '@algèbre@maths@calcul',
            'thematic' => 'thematic_5',
            'difficulty' => 2,
            'duration' => 0.10,
            'original_name' => '',
            'originInformation' => '',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Laurent',
            'proposedByLasName' => 'Guyard',
            'file' => 'file_3',
            'correction_file' => 'file_7',
            'created_by' => 'user_1',
        ],
        [
            'name' => 'Molière, le malade imaginaire',
            'course' => 'course_0',
            'chapter' => 'Chapitre 5',
            'keywords' => '@théatre@molière',
            'thematic' => 'thematic_2',
            'difficulty' => 2,
            'duration' => 1.842,
            'original_name' => 'Le Malade Imaginaire',
            'originInformation' => 'Livre de molière',
            'proposedByType' => '',
            'proposedByFirstName' => '',
            'proposedByLasName' => '',
            'file' => 'file_2',
            'correction_file' => 'file_8',
            'created_by' => 'user_2',
        ],
        [
            'name' => 'Paris Ville Lumière',
            'course' => 'course_0',
            'chapter' => 'Chapitre 2',
            'thematic' => 'thematic_1',
            'keywords' => '@paris@littérature@arts',
            'difficulty' => 2,
            'duration' => 2,
            'original_name' => '',
            'originInformation' => '',
            'proposedByType' => 'Enseignant',
            'proposedByFirstName' => 'Virginie',
            'proposedByLasName' => 'Hougron',
            'file' => 'file_1',
            'correction_file' => 'file_9',
            'created_by' => 'user_2',
        ],
    ];

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::EXERCISES as $i => $exerciseInfo) {
            $exercise = (new Exercise())
                ->setName($exerciseInfo['name'])
                ->setCourse($this->getReference($exerciseInfo['course']))
                ->setClassroom($this->getReference(ClassroomFixtures::REFERENCE_IDENTIFIER.random_int(0, count(ClassroomFixtures::CLASSROOMS) - 1)))
                ->setThematic($this->getReference($exerciseInfo['thematic']))
                ->setChapter($exerciseInfo['chapter'])
                ->setKeywords($exerciseInfo['keywords'])
                ->setDifficulty($exerciseInfo['difficulty'])
                ->setDuration($exerciseInfo['duration'])
                ->setOrigin($exerciseInfo['proposedByType'] ? null : $this->getReference(OriginFixtures::REFERENCE_IDENTIFIER.random_int(0, count(OriginFixtures::ORIGINS) - 1)))
                ->setoriginName($exerciseInfo['original_name'])
                ->setOriginInformation($exerciseInfo['originInformation'])
                ->setProposedByType($exerciseInfo['proposedByType'])
                ->setProposedByFirstName($exerciseInfo['proposedByFirstName'])
                ->setProposedByLasName($exerciseInfo['proposedByLasName'])
                ->setExerciseFile($this->getReference($exerciseInfo['file']))
                ->setCorrectionFile($this->getReference($exerciseInfo['correction_file']))
                ->setCreatedBy($this->getReference($exerciseInfo['created_by']))
                ->setIsOnline(rand(0,1));

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
