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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        $this->denyAccessUnlessGranted('anonymous');

        $form = $this->createForm(LoginType::class);

        return $this->render('security/login/index.html.twig', [
            'form' => $form->createView(),
            'google_url' => Google::getLoginPageUrl()
        ]);
    }

    #[Route('/login/forgot-password', name: 'login_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        MailerInterface $mailer,
        CustomJWT $customJWT,
        ManagerRegistry $managerRegistry,
        UserPasswordHasherInterface $userPasswordHasherInterface)
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

                            $this->addFlash("success", "Your password has been changed. Please login.");

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
                $this->addFlash("danger", "This link is invalid or has expired. Please resubmit your request.");
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
                    $this->addFlash("success", "You have received an email in your mailbox to change your password");
                    return $this->redirectToRoute("login");
                } else {
                    $this->addFlash("danger", "There is no user associated with this email or username.");
                }
            }
        }

        return $this->render('security/login/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
