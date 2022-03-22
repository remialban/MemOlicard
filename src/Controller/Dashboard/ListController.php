<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ListController extends AbstractController
{
    /**
     * @Route("/dashboard/list/{id}", name="list_view")
     */
    public function view(CardsList $cardsList,
        TranslatorInterface $translator,
        Request $request,
        ManagerRegistry $managerRegistry)
    {
        if ($cardsList->getUser() != $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        $removeForm = $this->createFormBuilder()
            ->getForm()
        ;

        $removeForm->handleRequest($request);

        if ($removeForm->isSubmitted() && $removeForm->isValid())
        {
            $manager = $managerRegistry->getManager();
            $manager->remove($cardsList);
            $manager->flush();
            $this->addFlash("success", $translator->trans('flash.list.delete_successful'));
            return $this->redirectToRoute("dashboard_home");
        }

        return $this->render('dashboard/lists/view.html.twig', [
            'cardsList' => $cardsList,
            'removeForm' => $removeForm->createView(),
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
