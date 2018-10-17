<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 17/10/18
 * Time: 17:01
 */

namespace App\Event;


use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCreatedEvent extends Event
{
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