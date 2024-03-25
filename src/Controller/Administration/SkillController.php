<?php

namespace App\Controller\Administration;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/skill')]
class SkillController extends AbstractController
{
    #[Route('/', name: 'app_administration_skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $skills = $skillRepository->findAll();

        $pagination = $paginator->paginate(
            $skills,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/skill/index.html.twig', [
            'skills' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_skill_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_skill_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager, Environment $twig): Response
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
        $entityManager->remove($skill);
        $entityManager->flush();

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/{id}/research', name: 'app_administration_thematic_research', methods: ['GET'])]
    public function research(Request $request, Skill $skill, EntityManagerInterface $entityManager, SkillRepository $skillRepository): JsonResponse
    {
        $query = $request->query->get('value');
        $skills = -$skillRepository->findBy(['name' => $query]);

        return new JsonResponse(['thematic' => $skills], Response::HTTP_OK);
    }
}
