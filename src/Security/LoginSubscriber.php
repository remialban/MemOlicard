<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\LogoutEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

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
            $session->getFlashBag()->add("success", $this->translator->trans('flash.auth.login_successful', ['name' => $user->getFirstName()]));
        }
    }

    public function onLogout(LogoutEvent $logoutEvent)
    {
        $session = $logoutEvent->getRequest()->getSession();

        if ($session instanceof Session)
        {
            $session->getFlashBag()->add("success", $this->translator->trans('flash.auth.logout'));
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
