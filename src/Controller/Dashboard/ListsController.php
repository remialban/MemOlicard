<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ListsController extends AbstractController
{
    #[Route('/dashboard/lists/{id}/edit', name: 'dashboard_cards_list_edit')]
    public function editCardsList(CardsList $cardsList, JWTTokenManagerInterface $JWTManager)
    {
        if ($cardsList->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/lists/edit.html.twig', [
            'cardsList' => $cardsList,
            'token' => $JWTManager->create($this->getUser()),
        ]);
    }
}
