<?php

namespace App\Controller;

use App\Entity\Course;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{course}', name: 'app_course')]
    public function index(Course $course, CourseRepository $courseRepository): Response
    {

        $exercises = $course->getExercises();
        $courses = $courseRepository->findAll();
        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'course' => $course,
            'courses' => $courses,
            'exercises' => $exercises,
        ]);
    }
}
