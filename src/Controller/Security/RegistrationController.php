<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use App\Security\OAuth\Google;
use App\Tool\CustomJWT;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route(path={
     *     "en": "/signin",
     *     "fr": "/inscription"
     * }, name="register")
     */
    public function index(
        User $user = null,
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $passwordHandler,
        MailerInterface $mailerInterface,
        CustomJWT $customJWT,
        UrlGeneratorInterface $router,
        TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('anonymous');
        if ($user)
        {
            $user = new User();
        }

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $entityManager = $doctrine->getManager();

            $hashedPassword = $passwordHandler->hashPassword($user, $user->getModifiedPassword());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', $translator->trans('flash.auth.register_successful'));
            $email = (new TemplatedEmail())
                ->from("remi.alban@hotmail.com")
                ->to($user->getEmail())
                ->subject("Welcome to MemOlicard ! Check your email adress")
                ->htmlTemplate("email/email_verification.html.twig")
                ->context([
                    'user' => $user,
                    'token' => $customJWT->generateToken([
                        'type' => 'emailVerification',
                        'email' => $user->getEmail(),
                    ]),
                ])
            ;
            $mailerInterface->send($email);
            return $this->redirectToRoute("login");
        }

        return $this->render('security/registration/index.html.twig', [
            'form' => $form->createView(),
            'google_url' => Google::getLoginPageUrl($router),
        ]);
    }

    /**
     * @Route("/login/check_email", name="check_email")
     */
    public function checkEmail(
        Request $request,
        UserRepository $userRepository,
        CustomJWT $customJWT,
        ManagerRegistry $managerRegistry,
        TranslatorInterface $translator
    )
    {
        $token = $request->get("token");
        if ($token)
        {
            try {
                $data = $customJWT->getData($token);
                if ($data['type'] == 'emailVerification')
                {
                    $user = $userRepository->findOneBy([
                        'email' => $data['email']
                    ]);
                    if ($user->getEmailIsChecked())
                    {
                        throw new Exception();
                    }
                    $user->setEmailIsChecked(true);
                    $managerRegistry->getManager()->persist($user);
                    $managerRegistry->getManager()->flush();
                    $this->addFlash("success", $translator->trans('auth.validation_successful'));
                    return $this->redirectToRoute('login');
                }
            } catch (SignatureInvalidException $exception) {}
        }
        throw $this->createNotFoundException();
    }
}
