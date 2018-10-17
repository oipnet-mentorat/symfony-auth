<?php
/**
 * Created by PhpStorm.
 * User: arnaud
 * Date: 17/10/18
 * Time: 15:31
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin')
            ->setPassword($this->encoder->encodePassword($user, 'admin2'))
            ->setIsActive(false)
            ->setEmail('admin@local.dev')
        ;

        $manager->persist($user);
        $manager->flush();
    }
}