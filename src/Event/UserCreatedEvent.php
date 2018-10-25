<?php

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class UserCreatedEvent
 * Evènement lancé lorsque l'on créé un utilisateur
 * @package App\Event
 */
class UserCreatedEvent extends Event
{
    // Nom de l'évènement
    const NAME = 'user.created';

    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}