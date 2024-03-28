<?php

namespace App\EventListener;

use App\Event\PasswordResetEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

final class PasswordResetListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PasswordResetEvent::class => 'onPasswordResetEvent',
        ];
    }

    public function onPasswordResetEvent(PasswordResetEvent $event): void
    {
        $lastname = $event->getLastname();
        $firstname = $event->getFirstname();
        $email = $event->getEmail();

        // Send an email to contact@lyceestvincent.net
        $email = (new Email())
            // ->from(new Address('noreply@mathindex.fr', 'Support MathIndex'))
            ->from('noreply@mathindex.fr')
            ->to('contact@lyceestvincent.net')
            ->subject(sprintf(
                'MathIndex: %s %s demande un changement de mot de passe.',
                $lastname,
                $firstname
            ))
            ->text(sprintf(
                '%s %s demande un changement de mot de passe.%sAdresse e-mail de la personne : "%s"',
                $lastname,
                $firstname,
                PHP_EOL,
                $email
            ));

        try {
            // Send the email
            // Check if the email was sent successfully
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            // Handle the case where sending the email failed
            var_dump('Failed to send email: '.$exception->getMessage());
            exit;
        }
    }
}
