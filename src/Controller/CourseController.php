<?php

namespace App\Controller;

use App\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    #[Route('/matiere/{course}', name: 'app_course')]
    public function index(Course $course): Response
    {
        $exercises = ($course->getExercises());
        // foreach($exercises as $exercises){
        //     var_dump($exercises->getName());
        // }
        // die;

        return $this->render('course/index.html.twig', [
            'controller_name' => 'CourseController',
            'course' => $course,
            'exercises' => $exercises,
        ]);
    }
}
