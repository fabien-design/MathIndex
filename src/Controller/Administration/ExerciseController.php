<?php

namespace App\Controller\Administration;

use App\Entity\Exercise;
use App\Entity\File;
use App\Form\ExerciseType;
use App\Repository\ExerciseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration/exercise')]
class ExerciseController extends AbstractController
{
    #[Route('/', name: 'app_administration_exercise_index', methods: ['GET'])]
    public function index(ExerciseRepository $exerciseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $exercises = $exerciseRepository->findAll();

        $pagination = $paginator->paginate(
            $exercises,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('administration/exercise/index.html.twig', [
            'exercises' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_administration_exercise_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise, ['validation_groups' => ['new']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['enonceFile']->getData()) {
                $file = new File($form['enonceFile']->getData());
                $entityManager->persist($file);
                $exercise->setExerciseFile($file);
            }
            if ($form['correctFile']->getData()) {
                $correctionFile = new File($form['correctFile']->getData());
                $entityManager->persist($correctionFile);
                $exercise->setCorrectionFile($correctionFile);
            }
            $entityManager->persist($exercise);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_exercise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/exercise/new.html.twig', [
            'exercise' => $exercise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_administration_exercise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Exercise $exercise, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciseType::class, $exercise, ['validation_groups' => ['edit']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form['enonceFile']->getData()) {
                $enonceFile = new File($form['enonceFile']->getData());
                $entityManager->persist($enonceFile);
                $exercise->setExerciseFile($enonceFile);
            }
            if ($form['correctFile']->getData()) {
                $correctionFile = new File($form['correctFile']->getData());
                $entityManager->persist($correctionFile);
                $exercise->setCorrectionFile($correctionFile);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_administration_exercise_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/exercise/edit.html.twig', [
            'exercise' => $exercise,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_administration_exercise_delete', methods: ['POST'])]
    public function delete(Request $request, Exercise $exercise, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exercise->getId(), $request->request->get('_token'))) {
            $entityManager->remove($exercise);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_administration_exercise_index', [], Response::HTTP_SEE_OTHER);
    }
}
