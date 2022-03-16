<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Form\LoginType;
use App\Tool\CustomJWT;
use VRia\Utils\NoDiacritic;
use App\Security\OAuth\Google;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FormAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private ManagerRegistry $managerRegistry;
    private FormFactoryInterface $formFactory;
    private UserPasswordHasherInterface $userPasswordHasherInterface;
    private CustomJWT $customJWT;
    private MailerInterface $mailerInterface;

    public function __construct(
        UserRepository $userRepository,
        ManagerRegistry $managerRegistry,
        FormFactoryInterface $formFactory,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        CustomJWT $customJWT,
        MailerInterface $mailerInterface)
    {
        $this->userRepository = $userRepository;
        $this->managerRegistry = $managerRegistry;
        $this->formFactory = $formFactory;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->customJWT = $customJWT;
        $this->mailerInterface = $mailerInterface;
    }
    public function supports(Request $request): bool
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);
        return $request->getPathInfo() == "/login" && $form->isSubmitted() && $form->isValid();
    }

    public function authenticate(Request $request): Passport
    {
        $form = $this->formFactory->create(LoginType::class);
        $form->handleRequest($request);

        $data = $form->getData();

        $criteria = [];

        if (filter_var($data['username'], FILTER_VALIDATE_EMAIL))
        {
            $criteria['email'] = $data['username'];
        } else {
            $criteria['username'] = $data['username'];
        }

        $user = $this->userRepository->findOneBy($criteria);

        if ($user)
        {
            if ($this->userPasswordHasherInterface->isPasswordValid($user, $data['password']))
            {
                if ($user->getEmailIsChecked())
                {
                    return new SelfValidatingPassport(new UserBadge(
                        $user->getEmail(),
                        function($userIdentifier)
                        {
                            return $this->userRepository->findOneBy([
                                'email' => $userIdentifier,
                            ]);
                        }                    
                    ), [
                        new RememberMeBadge(),
                    ]);
                } else 
                {
                    $email = (new TemplatedEmail())
                        ->from("remi.alban@hotmail.com")
                        ->to($user->getEmail())
                        ->subject("Welcome to MemOlicard ! Check your email adress")
                        ->htmlTemplate("email/email_verification.html.twig")
                        ->context([
                            'user' => $user,
                            'token' => $this->customJWT->generateToken([
                                'type' => 'emailVerification',
                                'email' => $user->getEmail(),
                            ]),
                        ])
                    ;
                    $this->mailerInterface->send($email);

                    $session = $request->getSession();
                    if ($session instanceof Session)
                    {
                        $session->getFlashBag()->add("warning", "You must confirm your registration. We have sent to you an email in order to confirm that you are the owner of this email address.");
                    }
                    throw new AuthenticationException();
                }
            } else {
                throw new AuthenticationException('Your password is incorrect.');
            }
        } else {
            throw new AuthenticationException('Your email or username is incorrect.');
        }
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if (empty($exception->getMessage()))
        {
            return null;
        }

        $session = $request->getSession();
        if ($session instanceof Session)
        {
            $session->getFlashBag()->add("danger", $exception->getMessage());
        }
        return null;
    }
}
