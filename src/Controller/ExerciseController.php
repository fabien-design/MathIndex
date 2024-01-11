<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExerciseController extends AbstractController
{
    #[Route('/exercise', name: 'app_exercise')]
    public function index(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Si l'utilisateur est connecté, forcer l'initialisation de la collection d'exercices
        if ($user) {
            $user->getExercises()->initialize(); //pas touché Important 
            $exercises = $user->getExercises();
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercises' => $exercises,
        ]);
    }
}
