<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use App\Repository\CardsListRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    #[Route('/dashboard/users/{username}', name: 'user_view')]
    public function dashboard($username, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy([
            'username' => $username,
        ]);
        if (!$user)
        {
            $this->createNotFoundException();
        }
        return $this->render("dashboard/user/view.html.twig", [
            'user' => $user,
        ]);
    }
}
