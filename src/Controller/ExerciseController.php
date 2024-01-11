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
            $user->getExercises()->initialize();
            $exercises = $user->getExercises();
        } else {
            // Gérer le cas où l'utilisateur n'est pas connecté
            // ...
        }

        return $this->render('exercise/index.html.twig', [
            'controller_name' => 'ExerciseController',
            'exercises' => $exercises,
        ]);
    }
}
