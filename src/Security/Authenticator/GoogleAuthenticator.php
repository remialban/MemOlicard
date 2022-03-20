<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use VRia\Utils\NoDiacritic;
use App\Security\OAuth\Google;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends AbstractAuthenticator
{
    private Google $google;
    private UserRepository $userRepository;
    private ManagerRegistry $managerRegistry;
    private RouterInterface $routerInterface;

    public function __construct(Google $google, UserRepository $userRepository, ManagerRegistry $managerRegistry, RouterInterface $routerInterface)
    {
        $this->google = $google;
        $this->userRepository = $userRepository;
        $this->managerRegistry = $managerRegistry;
        $this->routerInterface = $routerInterface;
    }
    public function supports(Request $request): bool
    {
        return $request->attributes->get("_route") == "login" && $request->query->has('code') && $request->query->get('service', false) == "google";
    }

    public function authenticate(Request $request): Passport
    {
        $response = $this->google->getCredentials($request);

        $user = $this->userRepository->findOneBy([
            'googleId' => $response->getId(),
        ]);

        if (!$user)
        {
            $user = $this->userRepository->findOneBy([
                'email' => $response->getEmail(),
            ]);

            if ($user)
            {
                $user = null;
                $session = $request->getSession();
                if ($session instanceof Session)
                {
                    $session->getFlashBag()->add('danger', 'You have already an account with the ' . $response->getEmail() . ' email.');
                    throw new AuthenticationException();
                }
            } else
            {
                $user = (new User())
                    ->setEmailIsChecked(true)
                    ->setFirstName($response->getFirstName())
                    ->setLastName($response->getLastName())
                    ->setEmail($response->getEmail())
                    ->setGoogleId($response->getId());
                $i = 0;
                do {
                    $i++;
                } while ($this->userRepository->findOneBy([
                    'username' => $this->getStartUsername($response->getFirstName(), $response->getLastName()) . $i
                ]));
                $user->setUsername($this->getStartUsername($response->getFirstName(), $response->getLastName()) . $i);
                $this->managerRegistry->getManager()->persist($user);
                $this->managerRegistry->getManager()->flush();
                $session = $request->getSession();
                if ($session instanceof Session)
                {
                    $session->getFlashBag()->add('success', 'Your registration has been validated you are now registered on our site.');
                }
            }
        }

        return new SelfValidatingPassport(new UserBadge($response->getId(), function($userIdentifier) {
            $user = $this->userRepository->findOneBy([
                'googleId' => $userIdentifier,
            ]);
            return $user;
        }), [
            // new RememberMeBadge(),
        ]);
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new RedirectResponse($this->routerInterface->generate("login"));
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
