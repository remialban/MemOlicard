<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use App\Form\CardsListType;
use App\Repository\CardsListRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/dashboard/home', name: 'dashboard_home')]
    public function index(CardsList $cardsList = null, Request $request, ManagerRegistry $doctrine, CardsListRepository $cardsListRepository): Response
    {
        if (!$cardsList)
        {
            $cardsList = new CardsList();
        }

        $form = $this->createForm(CardsListType::class, $cardsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $cardsList = $form->getData();
            $cardsList->setCreatedAt(new \DateTimeImmutable());
            $cardsList->setUpdatedAt(new \DateTimeImmutable());
            $cardsList->setUser($this->getUser());

            $entityManager = $doctrine->getManager();

            $entityManager->persist($cardsList);
            $entityManager->flush();

            return $this->redirectToRoute("dashboard_cards_list_edit", [
                "id" => $cardsList->getId()
            ]);
        }

        $cardsLists = $cardsListRepository->findBy([
            "user" => $this->getUser()
        ], [
            "updatedAt" => "DESC"
        ]);

        return $this->render('dashboard/home/index.html.twig', [
            'form' => $form->createView(),
            'cardsLists' => $cardsLists
        ]);
    }

    #[Route('/dashboard/cardslist/{id}', name: 'dashboard_cards_list_edit')]
    public function editCardsList(CardsList $cardsList)
    {
        if ($cardsList->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/home/cards_list_edit.html.twig', [
            'cardsList' => $cardsList
        ]);
    }
}
