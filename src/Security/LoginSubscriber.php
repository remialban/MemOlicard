<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginSubscriber implements EventSubscriberInterface
{
    public function onLoginSuccess(LoginSuccessEvent $loginSuccessEvent)
    {
        $session = $loginSuccessEvent->getRequest()->getSession();
        $user = $loginSuccessEvent->getPassport()->getUser();

        // $redirectUri = $loginSuccessEvent->getRequest()->get("redirect_uri", false);

        // if ($redirectUri)
        // {
        //     $loginSuccessEvent->setResponse(new RedirectResponse($redirectUri));
        // }
        if ($session instanceof Session && $user instanceof User)
        {
            $session->getFlashBag()->add("success", "Welcome " . $user->getFirstName() . "!");
        }
    }

    public function onLogout(LogoutEvent $logoutEvent)
    {
        $session = $logoutEvent->getRequest()->getSession();

        if ($session instanceof Session)
        {
            $session->getFlashBag()->add("success", "You have been successfully disconnected!");
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccess',
            LogoutEvent::class => 'onLogout',
        ];
    }

}
