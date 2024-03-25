<?php

namespace App\Controller\Administration;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

#[Route('/administration/course')]
class CourseController extends AbstractController
{
    #[Route('/', name: 'app_administration_course_index', methods: ['GET'])]
    public function index(CourseRepository $courseRepository): Response
    {
        return $this->render('administration/course/index.html.twig', [
            'courses' => $courseRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_administration_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($course);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administration_course_show', methods: ['GET'])]
    public function show(Course $course): Response
    {
        return $this->render('administration/course/show.html.twig', [
            'course' => $course,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_course_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/course/edit.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_course_delete', methods: ['POST'])]
    public function delete(Request $request, Course $course, EntityManagerInterface $entityManager, Environment $twig): Response
    {
        $user = $this->getUser();

        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur
        if (!$user) {
            // Rendre le template Twig
            $renderedTemplate = $twig->render('components/Alert.html.twig', [
                'type' => 'error',
                'message' => "Vous n'avez pas le droit de supprimer ce cours"
            ]);

            return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
        }

        // Supprimer les exercices associés au cours
        foreach ($course->getExercises() as $exercise) {
            $course->removeExercise($exercise);
            $entityManager->remove($exercise);
        }
        // Supprimer les compétences associées au cours
        foreach ($course->getSkills() as $skill) {
            $course->removeSkill($skill);
            $entityManager->remove($skill);
        }
        // Supprimer les thematiques associées au cours
        foreach ($course->getThematics() as $thematic) {
            $course->removeThematic($thematic);
            $entityManager->remove($thematic);
        }

        // Supprimer le cours lui-même
        $entityManager->remove($course);
        $entityManager->flush();

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(["html" => $renderedTemplate], Response::HTTP_OK);
    }

    #[Route('/{id}/research', name: 'app_administration_course_research', methods: ['GET'])]
    public function research(Request $request, Course $course, EntityManagerInterface $entityManager, CourseRepository $courseRepository): JsonResponse
    {
        $query = $request->query->get('value');
        $courses = $courseRepository->findBy(['name' => $query]);

        return new JsonResponse(['course' => $courses], Response::HTTP_OK);
    }
}
