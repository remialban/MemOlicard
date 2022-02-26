<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/dashboard/list/{id}", name="list_view")
     */
    public function view(CardsList $cardsList, JWTTokenManagerInterface $JWTManager)
    {
        if ($cardsList->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/lists/view.html.twig', [
            'cardsList' => $cardsList,
        ]);
    }

    /**
     * @Route("/dashboard/lists/{id}/edit", name="list_edit")
     */
    public function edit(CardsList $cardsList, JWTTokenManagerInterface $JWTManager)
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

    /**
     * @Route("/dashboard/lists/{id}/learn", name="list_learn")
     */
    public function learn(CardsList $cardsList, JWTTokenManagerInterface $JWTManager)
    {
        if ($cardsList->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/lists/learn.html.twig', [
            'cardsList' => $cardsList,
            'token' => $JWTManager->create($this->getUser()),
        ]);
    }
}
