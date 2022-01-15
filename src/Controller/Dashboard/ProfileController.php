<?php

namespace App\Controller\Dashboard;

use App\Entity\User;
use App\Form\Settings\ProfileType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/dashboard/profile', name: 'dashboard_profile')]
    public function index(Request $request, ManagerRegistry $managerRegistry, User $user = null): Response
    {
        $success = false;

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
            $success = "Your profile has been successfully modified";
        }

        return $this->render('dashboard/profile/index.html.twig', [
            'form' => $form->createView(),
            'success' => $success,
        ]);
    }

    #[Route('/dashboard/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}
