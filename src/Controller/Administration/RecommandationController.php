<?php

namespace App\Controller\Administration;

use App\Entity\Exercise;
use App\Entity\Recommandation;
use App\Form\RecommandationType;
use App\Repository\RecommandationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/recommandation')]
class RecommandationController extends AbstractController
{
    #[Route('/', name: 'app_administration_recommandation_index', methods: ['GET'])]
    public function index(RecommandationRepository $recommandationRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $recommandationRepository->findALl();

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/recommandation/index.html.twig', [
            'recommandations' => $pagination,
        ]);
    }


    #[Route('/new', name: 'app_administration_recommandation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recommandation = new Recommandation();
        $form = $this->createForm(RecommandationType::class, $recommandation);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($recommandation);
                $entityManager->flush();
                $this->addFlash('success', "La recommandation a bien été ajoutée !");

                return $this->redirectToRoute('app_administration_recommandation_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant l'ajout de la recommandation.");
        }

        return $this->render('administration/recommandation/new.html.twig', [
            'recommandation' => $recommandation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_recommandation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recommandation $recommandation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecommandationType::class, $recommandation);
        $form->handleRequest($request);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'La recommandation a bien été modifiée !');

                return $this->redirectToRoute('app_administration_recommandation_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la modification de la recommandation.");
        }

        return $this->render('administration/recommandation/edit.html.twig', [
            'recommandation' => $recommandation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_recommandation_delete', methods: ['POST'])]
    public function delete(Request $request, Recommandation $recommandation, EntityManagerInterface $entityManager, Environment $twig): Response
    {
        $user = $this->getUser();

        try {
            if (!$user) {
                $renderedTemplate = $twig->render('components/Alert.html.twig', [
                    'type' => 'error',
                    'message' => "Vous n'avez pas le droit de supprimer cette recommandation",
                ]);

                return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
            }

            
            $exercices = $recommandation->getExercises();
            foreach ($exercices as $exercice) {
                $exercice->setRecommandation(null);
                $entityManager->persist($exercice);
            }

            $entityManager->flush();
            $entityManager->remove($recommandation);
            $entityManager->flush();

        } catch (\Exception $e) {
            $this->addFlash('error', "Erreur pendant la suppression de la recommandation.");
        }

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'La suppression de la recommandation a bien été effectuée.',
        ]);

        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/{id}/research', name: 'app_administration_recommandation_research', methods: ['GET'])]
    public function research(Request $request, Recommandation $recommandation, EntityManagerInterface $entityManager, RecommandationRepository $recommandationRepository): JsonResponse
    {
        $query = $request->query->get('value');
        $recommandations = $recommandationRepository->findBy(['name' => $query]);

        return new JsonResponse(['recommandation' => $recommandations], Response::HTTP_OK);
    }
}
