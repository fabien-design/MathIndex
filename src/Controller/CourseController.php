<?php

namespace App\Controller;

use App\Entity\Course;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{course}', name: 'app_course')]
    public function index(Course $course, Request $request, PaginatorInterface $paginator, CourseRepository $courseRepository): Response
    {
        
        $courses = $courseRepository->findAll();
        $exercises = $course->getExercises();
        
        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            2
        );
        $newExercises = [];
        if($request->query->getInt('page') == 0){
            $original = new DateTime("now", new DateTimeZone('UTC'));
            $timezoneName = timezone_name_from_abbr("", 1*3600, false);
            $modifiedDate = $original->setTimezone(new DateTimezone($timezoneName));
            $modifiedDate->modify("-1 day");
            
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
            'courses' => $courses,
            'exercises' => $pagination,
            'newExercises' => $newExercises,
        ]);
    }
}
