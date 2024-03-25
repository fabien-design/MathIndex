<?php

namespace App\Controller\Administration;

use App\Entity\Origin;
use App\Form\OriginType;
use App\Repository\OriginRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/origin')]
class OriginController extends AbstractController
{
    #[Route('/', name: 'app_administration_origin_index', methods: ['GET'])]
    public function index(OriginRepository $originRepository): Response
    {
        return $this->render('administration/origin/index.html.twig', [
            'origins' => $originRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_administration_origin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $origin = new Origin();
        $form = $this->createForm(OriginType::class, $origin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($origin);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_origin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/origin/new.html.twig', [
            'origin' => $origin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_origin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Origin $origin, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OriginType::class, $origin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_origin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/origin/edit.html.twig', [
            'origin' => $origin,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_origin_delete', methods: ['POST'])]
    public function delete(Request $request, Origin $origin, EntityManagerInterface $entityManager, Environment $twig): Response
    {
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
        if (!$user) {
            // Rendre le template Twig
            $renderedTemplate = $twig->render('components/Alert.html.twig', [
                'type' => 'error',
                'message' => "Vous n'avez pas le droit de supprimer cette compétence",
            ]);

            return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
        }

        // Supprimer le cours lui-même
        $entityManager->remove($origin);
        $entityManager->flush();

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/{id}/research', name: 'app_administration_origin_research', methods: ['GET'])]
    public function research(Request $request, Origin $origin, EntityManagerInterface $entityManager, OriginRepository $originRepository): JsonResponse
    {
        $query = $request->query->get('value');
        $origins = -$originRepository->findBy(['name' => $query]);

        return new JsonResponse(['origin' => $origins], Response::HTTP_OK);
    }
}
