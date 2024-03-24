<?php

namespace App\Controller\Administration;

use App\Entity\Classroom;
use App\Form\ClassroomType;
use App\Repository\ClassroomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/classroom')]
class ClassroomController extends AbstractController
{
    #[Route('/', name: 'app_administration_classroom_index', methods: ['GET'])]
    public function index(ClassroomRepository $classroomRepository): Response
    {
        return $this->render('administration/classroom/index.html.twig', [
            'classrooms' => $classroomRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_administration_classroom_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $classroom = new Classroom();
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($classroom);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_classroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/classroom/new.html.twig', [
            'classroom' => $classroom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administration_classroom_show', methods: ['GET'])]
    public function show(Classroom $classroom): Response
    {
        return $this->render('administration/classroom/show.html.twig', [
            'classroom' => $classroom,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_classroom_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Classroom $classroom, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClassroomType::class, $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_classroom_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/classroom/edit.html.twig', [
            'classroom' => $classroom,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_classroom_delete', methods: ['POST'])]
    public function delete(Request $request, Classroom $classroom, EntityManagerInterface $entityManager, \Twig\Environment $twig): JsonResponse
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

        // if ($this->isCsrfTokenValid('delete'.$classroom->getId(), $request->request->get('_token'))) {
        $entityManager->remove($classroom);
        $entityManager->flush();
        // }

        // Rendre le template Twig
        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
        // return $this->redirectToRoute('app_administration_classroom_index', [], Response::HTTP_SEE_OTHER);
    }
}
