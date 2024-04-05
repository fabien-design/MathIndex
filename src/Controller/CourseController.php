<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{course}', name: 'app_course')]
    public function index(Course $course, Request $request, PaginatorInterface $paginator, CourseRepository $courseRepository): Response
    {
        $exercises = $course->getExercises();

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            2
        );
        $newExercises = [];
        if (0 == $request->query->getInt('page')) {
            $original = new \DateTime('now', new \DateTimeZone('UTC'));
            $timezoneName = timezone_name_from_abbr('', 1 * 3600, false);
            $modifiedDate = $original->setTimezone(new \DateTimeZone($timezoneName));
            $modifiedDate->modify('-'.$_ENV['NEW_EXERCISES_DAYS_OFFSET'].'day');

            foreach ($exercises as $exercise) {
                $exerciseDate = new \DateTime($exercise->getCreatedAt());
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
}
