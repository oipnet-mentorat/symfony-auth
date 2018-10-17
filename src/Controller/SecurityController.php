<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;
    /**
     * @var TokenStorageInterface
     */
    private $token;

    public function __construct(AuthenticationUtils $authenticationUtils, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, EventDispatcherInterface $dispatcher, TokenStorageInterface $token)
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->encoder = $encoder;
        $this->em = $em;
        $this->dispatcher = $dispatcher;
        $this->token = $token;
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $password = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password)->setIsActive(false)->setPlainPassword(null);

            $this->em->persist($user);
            $this->em->flush();

            $event = new UserCreatedEvent($user);
            $this->dispatcher->dispatch(UserCreatedEvent::NAME, $event);

            return $this->redirect('/');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/activate/{token}", name="activate")
     */
    public function activate($token)
    {
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['token' => $token])
        ;

        if (!$user) {
            throw new NotFoundHttpException("User not exist");
        }

        $user->setIsActive(true)
            ->setToken(null);

        $this->em->persist($user);
        $this->em->flush();

        $this->token->setToken(new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles()));

        return $this->redirect('/');
    }
}
