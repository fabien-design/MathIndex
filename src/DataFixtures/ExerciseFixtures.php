<?php

namespace App\DataFixtures;

use App\Entity\Exercise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ExerciseFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Exercice 1 maths
        $exercise1 = new Exercise();
        $exercise1->setName('Factorisation polynomiale');
        $exercise1->setCourse($this->getReference('mathématique-course'));
        $exercise1->setClassroom($this->getReference('seconde-class'));
        $exercise1->setThematic($this->getReference('calcul-intégral-maths'));
        $exercise1->setChapter('Chapitre 2');
        $exercise1->setKeywords('algèbre@maths@calcul');
        $exercise1->setDifficulty(3);
        $exercise1->setDuration(45.5);
        $exercise1->setOrigin($this->getReference('manuel-scolaire-origin'));
        $exercise1->setOriginName('Mathématiques avancées');
        $exercise1->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".');
        $exercise1->setProposedByType('Enseignant');
        $exercise1->setProposedByFirstName('Laurent');
        $exercise1->setProposedByLasName('Guyard');
        $exercise1->setExerciseFile($this->getReference('original-file1.pdf-file'));
        $exercise1->setCorrectionFile($this->getReference('original-file2.docx-file'));
        $exercise1->setCreatedBy($this->getReference('guyard-mathTeacher'));
        $manager->persist($exercise1);

        // Exercice 2 maths
        $exercise2 = new Exercise();
        $exercise2->setName("Dérivation d'une fonction exponentielle");
        $exercise2->setCourse($this->getReference('mathématique-course'));
        $exercise2->setClassroom($this->getReference('première-class'));
        $exercise2->setThematic($this->getReference('calcul-intégral-maths'));
        $exercise2->setChapter('Chapitre 3');
        $exercise2->setKeywords('algèbre@maths@calcul');
        $exercise2->setDifficulty(4);
        $exercise2->setDuration(200);
        $exercise2->setOrigin($this->getReference('manuel-scolaire-origin'));
        $exercise2->setOriginName('Mathématiques avancées');
        $exercise2->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".');
        $exercise2->setProposedByType('Enseignant');
        $exercise2->setProposedByFirstName('Laurent');
        $exercise2->setProposedByLasName('Guyard');
        $exercise2->setExerciseFile($this->getReference('original-file3.pdf-file'));
        $exercise2->setCorrectionFile($this->getReference('original-file4.pdf-file'));
        $exercise2->setCreatedBy($this->getReference('guyard-mathTeacher'));
        $manager->persist($exercise2);
        $manager->flush();

        // Exercice 3 maths
        $exercise3 = new Exercise();
        $exercise3->setName("Coordonnées");
        $exercise3->setCourse($this->getReference('mathématique-course'));
        $exercise3->setClassroom($this->getReference('3ème-class'));
        $exercise3->setThematic($this->getReference('géométrie-euclidienne-maths'));
        $exercise3->setChapter('Chapitre 5');
        $exercise3->setKeywords('algèbre@maths@calcul');
        $exercise3->setDifficulty(2);
        $exercise3->setDuration(150);
        $exercise3->setOrigin($this->getReference('manuel-scolaire-origin'));
        $exercise3->setOriginName('Mathématiques avancées');
        $exercise3->setOriginInformation('Exercice tiré du livre "Mathématiques avancées".');
        $exercise3->setProposedByType('Enseignant');
        $exercise3->setProposedByFirstName('Laurent');
        $exercise3->setProposedByLasName('Guyard');
        $exercise3->setExerciseFile($this->getReference('original-file5.pdf-file'));
        $exercise3->setCorrectionFile($this->getReference('original-file6.pdf-file'));
        $exercise3->setCreatedBy($this->getReference('guyard-mathTeacher'));
        $manager->persist($exercise3);

        // Exercice fr 1
        $exercise4 = new Exercise();
        $exercise4->setName("Molière, le malade imaginaire");
        $exercise4->setCourse($this->getReference('francais-course'));
        $exercise4->setClassroom($this->getReference('seconde-class'));
        $exercise4->setThematic($this->getReference('théatre-francais'));
        $exercise4->setChapter('Chapitre 5');
        $exercise4->setKeywords('théatre@molière');
        $exercise4->setDifficulty(2);
        $exercise4->setDuration(150);
        $exercise4->setOrigin($this->getReference('livre-origin'));
        $exercise4->setOriginName('Le Malade Imaginaire');
        $exercise4->setOriginInformation('Livre de molière');
        $exercise4->setProposedByType('Livre');
        $exercise4->setExerciseFile($this->getReference('original-file7.pdf-file'));
        $exercise4->setCorrectionFile($this->getReference('original-file8.pdf-file'));
        $exercise4->setCreatedBy($this->getReference('hougron-frTeacher'));
        $manager->persist($exercise4);

        // Exercice fr 2
        $exercise5 = new Exercise();
        $exercise5->setName("Paris Ville Lumière");
        $exercise5->setCourse($this->getReference('francais-course'));
        $exercise5->setClassroom($this->getReference('bts-1-class'));
        $exercise5->setThematic($this->getReference('culture-générale-francais'));
        $exercise5->setChapter('Chapitre 2');
        $exercise5->setKeywords('paris@littérature@arts');
        $exercise5->setDifficulty(2);
        $exercise5->setDuration(90);
        $exercise5->setOrigin($this->getReference('livre-origin'));
        $exercise5->setOriginName('classique&cie BTS');
        $exercise5->setOriginInformation('Johan Faerber');
        $exercise5->setProposedByType('Enseignant');
        $exercise5->setProposedByFirstName('Virginie');
        $exercise5->setProposedByLasName('Hougron');
        $exercise5->setExerciseFile($this->getReference('original-file9.pdf-file'));
        $exercise5->setCorrectionFile($this->getReference('original-file10.pdf-file'));
        $exercise5->setCreatedBy($this->getReference('hougron-frTeacher'));
        $manager->persist($exercise5);

        $manager->flush();
    }




    public function getDependencies() : array
    {
        return [
            ThematicFixtures::class,
            UserFixtures::class,
            OriginFixtures::class,
            FileFixtures::class,
            CourseFixtures::class,
            ClassroomFixtures::class
        ];
    }
}