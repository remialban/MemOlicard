<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Security\OAuth\Google;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/signin', name: 'register')]
    public function index(User $user = null, Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHandler): Response
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

            $this->addFlash('success', 'Your registration has been validated you are now registered on our site. Please login.');
            return $this->redirectToRoute("login");
        }

        return $this->render('security/registration/index.html.twig', [
            'form' => $form->createView(),
            'google_url' => Google::getLoginPageUrl(),
        ]);
    }
}
