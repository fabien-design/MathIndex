<?php

namespace App\Controller\Administration;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
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
    public function index(CourseRepository $courseRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $courses = $courseRepository->findAll();

        $pagination = $paginator->paginate(
            $courses,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/course/index.html.twig', [
            'courses' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($course);
                $entityManager->flush();
                $this->addFlash('success', "La matière a bien été sauvegardée !");
    
                return $this->redirectToRoute('app_administration_course_index', [], Response::HTTP_SEE_OTHER);
            }
        }catch(Exception $e){
            $this->addFlash('error', "Erreur pendant la création de la matière");
        }

        return $this->render('administration/course/new.html.twig', [
            'course' => $course,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Course $course, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', "La matière a bien été modifiée!");
    
                return $this->redirectToRoute('app_administration_course_index', [], Response::HTTP_SEE_OTHER);
            }
        }catch(Exception $e){
            $this->addFlash('error', 'Erreur lors de le modification de la matière !');
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

        try{
            if (!$user) {
                // Rendre le template Twig
                $renderedTemplate = $twig->render('components/Alert.html.twig', [
                    'type' => 'error',
                    'message' => "Vous n'avez pas le droit de supprimer ce cours",
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

        }catch(Exception $e){
            $this->addFlash('error', "Erreur pendant la suppression de la matière");
        }
        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
    }
}
