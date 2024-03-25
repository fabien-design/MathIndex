<?php

namespace App\Controller\Administration;

use App\Entity\Thematic;
use App\Form\ThematicType;
use App\Repository\ThematicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/thematic')]
class ThematicController extends AbstractController
{
    #[Route('', name: 'app_administration_thematic_index', methods: ['GET'])]
    public function index(ThematicRepository $thematicRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $thematics = $thematicRepository->findAll();

        $pagination = $paginator->paginate(
            $thematics,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/thematic/index.html.twig', [
            'thematics' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_thematic_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $thematic = new Thematic();
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($thematic);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/thematic/new.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_thematic_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Thematic $thematic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/thematic/edit.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_thematic_delete', methods: ['POST'])]
    public function delete(Request $request, Thematic $thematic, EntityManagerInterface $entityManager, Environment $twig): Response
    {
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
        if (!$user) {
            // Rendre le template Twig
            $renderedTemplate = $twig->render('components/Alert.html.twig', [
                'type' => 'error',
                'message' => "Vous n'avez pas le droit de supprimer cette thématique",
            ]);

            return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
        }

        // Supprimer les exercices associés au cours
        foreach ($thematic->getExercises() as $exercise) {
            $thematic->removeExercise($exercise);
            $entityManager->remove($exercise);
        }

        // Supprimer le cours lui-même
        $entityManager->remove($thematic);
        $entityManager->flush();

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/{id}/research', name: 'app_administration_thematic_research', methods: ['GET'])]
    public function research(Request $request, Thematic $thematic, EntityManagerInterface $entityManager, ThematicRepository $thematicRepository): JsonResponse
    {
        $query = $request->query->get('value');
        $thematics = $thematicRepository->findBy(['name' => $query]);

        return new JsonResponse(['thematic' => $thematics], Response::HTTP_OK);
    }
}
