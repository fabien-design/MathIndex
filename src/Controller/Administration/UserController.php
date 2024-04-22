<?php

namespace App\Controller\Administration;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

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
        $users = $userRepository->findBy([], ['id' => 'DESC']);

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
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Le contributeur a bien été créé !');

                return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur pendant la création du contributeur !');
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

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                if (null != $user->getPlainPassword()) {
                    $user->setPassword($passwordHasher->hashPassword($user, $user->getPlainPassword()));
                }
                $entityManager->flush();
                $this->addFlash('success', 'Les modifications ont bien été prises en compte !');

                return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur pendant la modification du contributeur !');
        }

        return $this->render('administration/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_administration_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $userToDelete, EntityManagerInterface $entityManager, Environment $twig): Response
    {
<<<<<<< HEAD
        $user = $this->getUser();

        try{
            if (!$user) {
                // Rendre le template Twig
                $renderedTemplate = $twig->render('components/Alert.html.twig', [
                    'type' => 'error',
                    'message' => "Vous n'avez pas le droit de supprimer cette utilisateur",
                ]);
                
                return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_UNAUTHORIZED);
            }

            // Supprimer le cours lui-même
            $entityManager->remove($userToDelete);
            $entityManager->flush();

        }catch(Exception $e){
            $this->addFlash('error', "Erreur pendant la suppression de l'utilisateur.");
        }
        // Si l'utilisateur n'est pas connecté, retourner une réponse d'erreur

        $renderedTemplate = $twig->render('components/Alert.html.twig', [
            'type' => 'success',
            'message' => 'Suppression réussie',
        ]);

        // Retourner une réponse JSON avec le résultat du rendu du template
        return new JsonResponse(['html' => $renderedTemplate], Response::HTTP_OK);
=======
        try {
            if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
                $entityManager->remove($user);
                $entityManager->flush();
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur pendant la suppression du contributeur !');
        }

        return $this->redirectToRoute('app_administration_user_index', [], Response::HTTP_SEE_OTHER);
>>>>>>> a2bf71c (Fix Remove exercice)
    }
}
