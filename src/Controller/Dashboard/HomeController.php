<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use App\Form\CardsListType;
use App\Repository\CardsListRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route(path={
     *     "en": "/dashboard",
     *     "fr": "/tableau-de-bord"
     * }, name="dashboard_home")
     */
    public function dashboard(CardsList $cardsList = null,
        Request $request,
        ManagerRegistry $doctrine,
        CardsListRepository $cardsListRepository,
        TranslatorInterface $translator): Response
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

            $this->addFlash('success', $translator->trans('flash.list.create_successful'));

            return $this->redirectToRoute("list_edit", [
                "id" => $cardsList->getId()
            ]);
        }

        $cardsLists = $cardsListRepository->findBy([
            "user" => $this->getUser()
        ], [
            "updatedAt" => "DESC"
        ], 4);

        return $this->render('dashboard/index.html.twig', [
            'form' => $form->createView(),
            'cardsLists' => $cardsLists
        ]);
    }
}
