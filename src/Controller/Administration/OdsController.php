<?php
// src/Controller/Administration/OdsController.php
namespace App\Controller\Administration;

use App\Entity\Exercise;
use App\Entity\Thematic;
use App\Entity\Origin;
use App\Entity\User;
use App\Entity\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\OdsUploadType;

class OdsController extends AbstractController
{
    #[Route('/administration/transfert', name: 'administration_transfert_ods')]
    public function transfert(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OdsUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $odsFile = $form->get('odsFile')->getData();

            if ($odsFile) {
                try {
                    // Lire le fichier CSV et transformer les données
                    $data = array_map('str_getcsv', file($odsFile->getPathname()));
                    $header = array_shift($data);

                    foreach ($data as $row) {
                        $exercise = new Exercise();

                        foreach ($header as $index => $column) {
                            switch ($column) {
                                case 'Id':
                                    //ID est auto incrementé donc on ne le set pas
                                    break;
                                case 'Theme':
                                    $thematic = $this->findOrCreateEntity($entityManager, Thematic::class, $row[$index]);
                                    $exercise->setThematic($thematic);
                                    break;
                                case 'Source':
                                    $origin = $this->findOrCreateEntity($entityManager, Origin::class, $row[$index]);
                                    $exercise->setOrigin($origin);
                                    break;
                                case 'Professeur':
                                    $user = $this->findOrCreateEntity($entityManager, User::class, $row[$index]);
                                    $exercise->setCreatedBy($user);
                                    break;
                                case 'Info1':
                                    $exercise->setOriginName($row[$index]);
                                    break;
                                case 'Info2':
                                    $exercise->setOriginInformation($row[$index]);
                                    break;
                                case 'Fichier':
                                    $file = $this->findOrCreateEntity($entityManager, File::class, $row[$index]);
                                    $exercise->setExerciseFile($file);
                                    break;
                                case 'Mot_Clef':
                                    $exercise->setKeywords($row[$index]);
                                    break;
                                case 'Corrige':
                                    $correctionFile = $this->findOrCreateEntity($entityManager, File::class, $row[$index]);
                                    $exercise->setCorrectionFile($correctionFile);
                                    break;
                                case 'Duree':
                                    $exercise->setDuration($row[$index]);
                                    break;
                                case 'Difficulte':
                                    $exercise->setDifficulty($row[$index]);
                                    break;
                                case 'Type':
                                    $exercise->setProposedByType($row[$index]);
                                    break;
                                case 'Original':
                                    $exercise->setName($row[$index]);
                                    break;
                            }
                        }

                        // Vérifie que la colonne 'name' n'est pas nulle
                        if ($exercise->getName() === null) {
                            throw new \Exception('La colonne "name" ne peut pas être nulle.');
                        }

                        $entityManager->persist($exercise);
                    }

                    $entityManager->flush();

                    $this->addFlash('success', 'Les données ont été transférées avec succès.');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
                }
            }
        }

        return $this->render('administration/transfert.php.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function findOrCreateEntity(EntityManagerInterface $entityManager, string $entityClass, $id)
    {
        $entity = $entityManager->getRepository($entityClass)->find($id);

        if (!$entity) {
            $entity = new $entityClass();
            $entity->setId($id);
            $entityManager->persist($entity);
        }

        return $entity;
    }
}