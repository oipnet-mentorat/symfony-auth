<?php

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class UserCreatedSubcriber implements EventSubscriberInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(\Swift_Mailer $mailer, Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendActivationMail(UserCreatedEvent $event)
    {
        $user = $event->getUser();

        $message = (new \Swift_Message('Registration'))
            ->setFrom("admin@local.dev")
            ->setTo($user->getEmail())
            ->setBody(
                $this->twig->render('emails/registration.html.twig', compact('user'), 'text/html')
            )
        ;

        $this->mailer->send($message);
    }

    public static function getSubscribedEvents()
    {
        return [
           UserCreatedEvent::NAME => 'sendActivationMail',
        ];
    }
}
