<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 17/10/18
 * Time: 15:42
 */

namespace App\Security;


use App\Entity\User;
use App\Exceptions\AccountInactiveException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User) {
            return;
        }

        if (! $user->isActive()) {
            throw new AccountInactiveException('user is not active');
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}