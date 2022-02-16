<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Form\LoginType;
use VRia\Utils\NoDiacritic;
use App\Security\OAuth\Google;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class FormAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private UserRepository $userRepository,
        private ManagerRegistry $managerRegistry,
        private FormFactoryInterface $formFactory,
        private UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        
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
                return new SelfValidatingPassport(new UserBadge(
                    $user->getEmail(),
                    function($userIdentifier)
                    {
                        return $this->userRepository->findOneBy([
                            'email' => $userIdentifier,
                        ]);
                    }                    
                ));
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
        $session = $request->getSession();
        if ($session instanceof Session)
        {
            $session->getFlashBag()->add("danger", $exception->getMessage());
        }
        return null;
    }

    public function getStartUsername(string $firstName, string $lastName): string
    {
        $temp = strtolower(NoDiacritic::filter($firstName . $lastName));
        $username = "";
        foreach (str_split($temp) as $letter) {
            $letters = str_split('abcdefghijklmnopqrstuvwxyz');
            if (in_array($letter, $letters))
            {
                $username = $username . $letter;
            }
        }
        return $username;
    }
}
