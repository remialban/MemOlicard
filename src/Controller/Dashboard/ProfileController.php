<?php

namespace App\Controller\Dashboard;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/dashboard/profile', name: 'dashboard_profile')]
    public function index(): Response
    {
        return $this->render('dashboard/profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }

    #[Route('/dashboard/logout', name: 'logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('login');
    }
}
