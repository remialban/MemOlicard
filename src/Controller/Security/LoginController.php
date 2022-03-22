<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Tool\CustomJWT;
use App\Security\OAuth\Google;
use App\Form\ForgotPasswordType;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Settings\Security\CreatePasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        UrlGeneratorInterface $router): Response
    {
        $this->denyAccessUnlessGranted('anonymous');

        $form = $this->createForm(LoginType::class);

        return $this->render('security/login/index.html.twig', [
            'form' => $form->createView(),
            'google_url' => Google::getLoginPageUrl($router)
        ]);
    }

    /**
     * @Route("/login/forgot-password", name="login_forgot_password")
     */
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        CustomJWT $customJWT,
        ManagerRegistry $managerRegistry,
        UserPasswordHasherInterface $userPasswordHasherInterface,
        TranslatorInterface $translator)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        $token = $request->get("token");
        if ($token)
        {
            try {
                $payload = $customJWT->getData($token);
                if ($payload['type'] == "forgotPassword")
                {
                    $currentDate = new \DateTimeImmutable();
                    $tokenDate = \DateTimeImmutable::createFromFormat(\DateTimeImmutable::ATOM, $payload['expireAt']);
                    if ($tokenDate > $currentDate)
                    {
                        $user = $userRepository->findOneBy([
                            'email' => $payload['email']
                        ]);
                        $form = $this->createForm(CreatePasswordType::class, $user);
                        $form->handleRequest($request);
                        
                        if ($form->isSubmitted() && $form->isValid())
                        {
                            $user = $form->getData();

                            $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $user->getModifiedPassword());
                            $user->setPassword($hashedPassword);
            
                            $managerRegistry->getManager()->persist($user);
                            $managerRegistry->getManager()->flush();

                            $this->addFlash("success", $translator->trans('flash.auth.forgot_password.password_update_successful'));

                            return $this->redirectToRoute("login");
                        }

                    } else
                    {
                        throw new Exception();
                    }
                } else
                {
                    throw new Exception();
                }
            } catch (Exception $exception)
            {
                $this->addFlash("danger", $translator->trans('flash.auth.forgot_password.expired_link'));
                return $this->redirectToRoute("login_forgot_password");
            }

        } else {
            if ($form->isSubmitted() && $form->isValid())
            {
                $contentForm = $form->getData();
                $username = $contentForm['username'];
    
                $criteria = [];
    
                if (filter_var($username, FILTER_VALIDATE_EMAIL))
                {
                    $criteria['email'] = $username;
                } else {
                    $criteria['username'] = $username;
                }
    
                $user = $userRepository->findOneBy($criteria);
    
                if ($user)
                {
                    $currentDate = new \DateTimeImmutable();
                    $newDate = $currentDate->modify("+15 minute");
    
                    $payload = [
                        "type" => "forgotPassword",
                        "email" => $user->getEmail(),
                        "expireAt" => $newDate->format(\DateTimeImmutable::ATOM),
                    ];
    
                    $email = (new TemplatedEmail())
                        ->from("remi.alban@hotmail.com")
                        ->to($user->getEmail())
                        ->subject("Forgot password")
                        ->htmlTemplate('email/forgot_password.html.twig')
                        ->context([
                            'user' => $user,
                            'token' => $customJWT->generateToken($payload),
                        ])
                    ;
                    $mailer->send($email);
                    $this->addFlash("success", $translator->trans('flash.auth.forgot_password.sent_email'));
                    return $this->redirectToRoute("login");
                } else {
                    $this->addFlash("danger", $translator->trans('flash.auth.forgot_password.user_not_found'));
                }
            }
        }

        return $this->render('security/login/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
