<?php

namespace App\Controller\Administration;

use App\Entity\Exercise;
use App\Entity\File;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/exercise')]
class ExerciseController extends AbstractController
{
    #[Route('/', name: 'app_administration_exercise_index', methods: ['GET'])]
    public function index(ExerciseRepository $exerciseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $exercises = $exerciseRepository->findBy([], ['createdAt' => 'DESC']);

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('administration/exercise/index.html.twig', [
            'exercises' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_exercise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise, ['validation_groups' => ['new']]);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form['enonceFile']->getData()) {
                    $file = new File($form['enonceFile']->getData());
                    $entityManager->persist($file);
                    $exercise->setExerciseFile($file);
                }
                if ($form['correctFile']->getData()) {
                    $correctionFile = new File($form['correctFile']->getData());
                    $entityManager->persist($correctionFile);
                    $exercise->setCorrectionFile($correctionFile);
                }
                $entityManager->persist($exercise);
                $exercise->setCreatedBy($this->getUser());
                $entityManager->flush();
                $this->addFlash('success', "L'exercice a bien été ajouté !");

                return $this->redirectToRoute('app_administration_exercise_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->render('administration/exercise/new.html.twig', [
                'exercise' => $exercise,
                'form' => $form,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de le l\'ajout de l\'exercice');
        }
    }

    #[Route('/{id}/edit', name: 'app_administration_exercise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exercise $exercise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form['enonceFile']->getData()) {
                    $enonceFile = new File($form['enonceFile']->getData());
                    $entityManager->persist($enonceFile);
                    $exercise->setExerciseFile($enonceFile);
                }
                if ($form['correctFile']->getData()) {
                    $correctionFile = new File($form['correctFile']->getData());
                    $entityManager->persist($correctionFile);
                    $exercise->setCorrectionFile($correctionFile);
                }

                $entityManager->flush();
                $this->addFlash('success', "L'exercice a bien été modifié!");

                return $this->redirectToRoute('app_administration_exercise_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la modification de l'exercice");
        }

        return $this->render('administration/exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_exercise_delete', methods: ['POST'])]
    public function delete(Request $request, Exercise $exercise, EntityManagerInterface $entityManager, Environment $twig, FileRepository $fileRepository): JsonResponse
    {
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
        if (!$user) {
            // Rendre le template Twig
            $renderedTemplate = $twig->render('components/Alert.html.twig', [
                'type' => 'error',
                'message' => "Vous n'avez pas le droit de supprimer cette classe",
            ]);

            return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
        }

        // Supprimer les fichiers associés à la classe
        $exerciseCorrectionFile = $exercise->getCorrectionFile();
        $exerciseExerciseFile = $exercise->getExerciseFile();
        $exercise->removeFiles();
        $entityManager->remove($exercise);
        $entityManager->remove($exerciseExerciseFile);
        $entityManager->remove($exerciseCorrectionFile);
        $entityManager->flush();

        // Rendre le template Twig
        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }
}
