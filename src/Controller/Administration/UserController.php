<?php

namespace App\Controller\Administration;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/administration')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_administration_user_redirectToIndex', methods: ['GET'])]
    public function redirectToIndex(): Response
    {
        return $this->redirectToRoute('app_administration_user_index');
    }

    #[Route('/user', name: 'app_administration_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $users = $userRepository->findAll();

        $pagination = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('administration/user/index.html.twig', [
            'users' => $pagination,
        ]);
    }

    #[Route('/user/new', name: 'app_administration_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['user_roles' => $user->getRoles(), 'validation_groups' => ['new']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_administration_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('administration/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'app_administration_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, ['user_roles' => $user->getRoles()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null != $user->getPlainPassword()) {
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('administration/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/user/{id}', name: 'app_administration_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
