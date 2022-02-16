<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\Settings\Security\CreatePasswordType;
use App\Form\Settings\ProfileType;
use App\Form\Settings\Security\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SettingsController extends AbstractController
{
    #[Route('/dashboard/settings', name: 'dashboard_profile')]
    public function profile(Request $request, ManagerRegistry $managerRegistry, User $user = null): Response
    {
        if (!$user)
        {
            $user = $this->getUser();
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();
            $doctrine = $managerRegistry->getManager();
            $doctrine->persist($user);
            $doctrine->flush();

            $this->addFlash("success", "Your profile has been successfully modified!");
        }

        return $this->render('dashboard/settings/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/settings/security', name: 'dashboard_security')]
    public function security(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $userPasswordHasherInterface, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if ($user instanceof User)
        {
            if ($user->getPassword())
            {
                $form = $this->createForm(ChangePasswordType::class, $user);
            } else {
                $form = $this->createForm(CreatePasswordType::class, $user);
            }
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();
    
                $hashedPassword = $userPasswordHasherInterface->hashPassword($user, $user->getModifiedPassword());
                $user->setPassword($hashedPassword);
    
                $em = $managerRegistry->getManager();
                $em->persist($user);
                $em->flush();
    
                $this->addFlash('success', "The password has been successfully modified");
            }
    
            return $this->render('dashboard/settings/security.html.twig', [
                'form' => $form->createView(),
            ]);   
        }
    }

    #[Route('/dashboard/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}
