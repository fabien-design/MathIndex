<?php

namespace App\Controller\Administration;

use App\Entity\Origin;
use App\Form\OriginType;
use App\Repository\OriginRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(OriginRepository $originRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $origins = $originRepository->findAll();

        $pagination = $paginator->paginate(
            $origins,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/origin/index.html.twig', [
            'origins' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_origin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $origin = new Origin();
        $form = $this->createForm(OriginType::class, $origin);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($origin);
                $entityManager->flush();
                $this->addFlash('success', "L'origine a bien été ajoutée !");

                return $this->redirectToRoute('app_administration_origin_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant l\'ajout de l\'origine.");
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

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'L\'origine a bien été modifiée !');

                return $this->redirectToRoute('app_administration_origin_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la modification de l'origine.");
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

        try {
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
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la suppression de l'origine.");
        }

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'La suppression de l\'origine a bien été effectuée.',
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
