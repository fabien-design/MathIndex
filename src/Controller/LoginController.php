<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\PasswordResetEvent;
use App\Form\LoginType;
use App\Form\ResetPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security as SecurityBundle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        // Get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // Last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // You can handle the login logic here
            // Typically, you will authenticate the user using Symfony's Security component

            // Example:
            $user = $this->getUser();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'form' => $form->createView(),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SecurityBundle $security)
    {
        // logout the user in on the current firewall
        $security->logout();

        // you can also disable the csrf logout
        $security->logout(false);

        return $this->redirectToRoute('app_home');
    }

    #[Route('/reset-password', name: 'app_reset_password')]
    public function passwordReset(Request $request, EventDispatcherInterface $eventDispatcher, SessionInterface $session)
    {
        $alreadySend = $session->get('reset_password_email');
        $canHaveAccessToForm = true;
        if (null !== $alreadySend) {
            $valeur = $alreadySend[0];
            $time = $alreadySend[1];
            if (time() - $time > 5 * 60) {
                // La variable de session a expiré
                $session->remove('reset_password_email');
            } else {
                $canHaveAccessToForm = false;
            }
        }
        if (!$canHaveAccessToForm) {
            $this->addFlash('error', 'Vous devez attendre 5 minutes pour changer de mot de passe. il reste '.(5 * 60 - (time() - $time)).' secondes.');

            return $this->redirectToRoute('app_home');
        }

        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // ... Handle form submission and retrieve user and new password ...
            $lastname = strtoupper(htmlspecialchars(strip_tags($form['lastname']->getData())));
            $firstname = strtoupper(htmlspecialchars(strip_tags($form['firstname']->getData())));
            $email = strtolower(strip_tags($form['email']->getData()));
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $eventDispatcher->dispatch(new PasswordResetEvent($lastname, $firstname, $email));
                // Notifying the user that the mail has been send
                $this->addFlash('success', 'Votre e-mail de changement de mot de passe a été envoyé.');
                $session->set('reset_password_email', [true, time()]);

                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('login/reset_password.html.twig', [
            'form' => $form,
        ]);
    }
}
