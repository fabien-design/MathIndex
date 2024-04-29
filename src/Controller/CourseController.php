<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Skill;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{course}', name: 'app_course')]
    public function index(Course $course, Request $request, PaginatorInterface $paginator, CourseRepository $courseRepository): Response
    {
        $exercises = $course->getExercises();
        // Convert the Collection to a regular array
        $exercisesArray = $exercises->toArray();
        // Reverse the array
        $reversedExercisesArray = array_reverse($exercisesArray);
        // Convert the reversed array back to a Collection
        $reversedExercisesCollection = new ArrayCollection($reversedExercisesArray);
        $exercises = $reversedExercisesCollection;

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            5
        );
        $newExercises = [];
        if (0 == $request->query->getInt('page')) {
            $original = new \DateTime('now', new \DateTimeZone('UTC'));
            $timezoneName = timezone_name_from_abbr('', 1 * 3600, false);
            $modifiedDate = $original->setTimezone(new \DateTimeZone($timezoneName));
            $modifiedDate->modify('-'.$_ENV['NEW_EXERCISES_DAYS_OFFSET'].'day');

            foreach ($exercises as $exercise) {
                $exerciseDate = new \DateTime($exercise->getCreatedAt()->format('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
                if ($exerciseDate > $modifiedDate) {
                    array_push($newExercises, $exercise);
                }
            }
        }

        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'course' => $course,
            'exercises' => $pagination,
            'newExercises' => $newExercises,
        ]);
    }

    #[Route('/getSkillsCourse/{id}', name: 'app_course_skills', methods: ['GET'])]
    public function getCoursesSkills(Course $course, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($course) {
            $skills = $entityManager->getRepository(Skill::class)->findBy(['course' => $course]);
            $arraySkills = [];
            foreach ($skills as $skill) {
                $array = [
                    'id' => $skill->getId(),
                    'name' => $skill->getName(),
                ];
                array_push($arraySkills, $array);
            }

            return new JsonResponse($arraySkills, Response::HTTP_OK);
        }

        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }
}
