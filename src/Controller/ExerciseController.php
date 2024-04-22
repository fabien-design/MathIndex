<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Entity\File;
use App\Form\ExerciseType;
use App\Form\ResearchType;
use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ExerciseController extends AbstractController
{
    #[Route('/exercises', name: 'app_exercise')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur est connecté, forcer l'initialisation de la collection d'exercices
        if ($user) {
            $user->getExercises()->initialize(); // pas touché Important
            $exercises = $user->getExercises();
            // Convert the Collection to a regular array
            $exercisesArray = $exercises->toArray();
            // Reverse the array
            $reversedExercisesArray = array_reverse($exercisesArray);
            // Convert the reversed array back to a Collection
            $reversedExercisesCollection = new ArrayCollection($reversedExercisesArray);
            $exercises = $reversedExercisesCollection;
        } else {
            return $this->redirectToRoute('app_login');
        }

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercises' => $pagination,
        ]);
    }

    #[Route('/exercises/{id}/delete', name: 'app_api_exercise_delete', methods: ['POST'])]
    public function delete(Request $request, Exercise $exercise, EntityManagerInterface $entityManager, \Twig\Environment $twig): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        try {
            // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
            if (!$user) {
                // Rendre le template Twig
                $renderedTemplate = $twig->render('components/Alert.html.twig', [
                    'type' => 'error',
                    'message' => "Vous n'avez pas le droit de supprimer cet exercice",
                ]);

                return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
            }
            $entityManager->remove($exercise);
            $entityManager->flush();
            
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->addFlash('error', "Erreur pendant la suppression de l'exercice");
        }

        // Rendre le template Twig
        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/exercise/research', name: 'app_research')]
    public function research(ExerciseRepository $exerciseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $form = $this->createForm(ResearchType::class)->handleRequest($request);
        $exercises = $exerciseRepository->findBy([], ['createdAt' => 'DESC']);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $classroom = $formData->getClassroom();
            $thematic = $formData->getThematic();
            $course = $formData->getCourse();
            $keywords = explode('/', $formData->getKeywords());

            $exercises = $exerciseRepository->findExercisesByResearch($thematic, $classroom, $course, $keywords);
        }

        $results = count($exercises);

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('exercise/research.html.twig', [
            'researchForm' => $form,
            'exercises' => $pagination,
            'results' => $results,
        ]);
    }

    #[Route('/exercise/new', name: 'app_exercise_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_STUDENT')]
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
                    $exercise->setExerciseFile($file)
                    ->setCreatedBy($this->getUser());
                }
                if ($form['correctFile']->getData()) {
                    $correctionFile = new File($form['correctFile']->getData());
                    $entityManager->persist($correctionFile);
                    $exercise->setCorrectionFile($correctionFile);
                }
                $entityManager->persist($exercise);
                $entityManager->flush();
                $this->addFlash('success', "L'exercice a bien été ajouté !");

                return $this->redirectToRoute('app_exercise', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant l'ajout de l'exercice");
        }

        return $this->render('exercise/submit/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/exercise/{id}/edit', name: 'app_exercise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Exercise $exercise): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                if ($form['enonceFile']->getData()) {
                    $file = new File($form['enonceFile']->getData());
                    $entityManager->persist($file);
                    $exercise->setExerciseFile($file)
                    ->setCreatedBy($this->getUser());
                }
                if ($form['correctFile']->getData()) {
                    $correctionFile = new File($form['correctFile']->getData());
                    $entityManager->persist($correctionFile);
                    $exercise->setCorrectionFile($correctionFile);
                }
                $entityManager->flush();
                $this->addFlash('success', "L'exercice a bien été modifié!");

                return $this->redirectToRoute('app_exercise', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la modification de l'exercice");
        }

        return $this->render('exercise/submit/edit.html.twig', [
            'form' => $form,
            'exercise' => $exercise,
        ]);
    }
}
