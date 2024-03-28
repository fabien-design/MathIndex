<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ResearchType;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    #[Route('/exercises', name: 'app_exercise')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur est connecté, forcer l'initialisation de la collection d'exercices
        if ($user) {
            $user->getExercises()->initialize(); // pas touché Important
            $exercises = $user->getExercises();
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercises' => $exercises,
        ]);
    }

    #[Route('/exercises/{id}/delete', name: 'app_api_exercise_delete', methods: ['POST'])]
    public function delete(Request $request, Exercise $exercise, EntityManagerInterface $entityManager, \Twig\Environment $twig): JsonResponse
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
        if (!$user) {
            // Rendre le template Twig
            $renderedTemplate = $twig->render('components/Alert.html.twig', [
                'type' => 'error',
                'message' => "Vous n'avez pas le droit de supprimer cet exercice",
            ]);

            return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
        }

        // Supprimer l'exercice
        $entityManager->remove($exercise);
        $entityManager->flush();

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
        $exercises = $exerciseRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $classroom = $formData->getClassroom();
            $thematic = $formData->getThematic();
            $keywords = explode('/', $formData->getKeywords());

            $exercises = $exerciseRepository->findExercisesByResearch($thematic, $classroom, $keywords);
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
}
