<?php

namespace App\Controller\Dashboard;

use App\Entity\CardsList;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

/**
 * @Route(path={
 *     "en": "/lists",
 *     "fr": "/listes"
 * }, name="list_")
 * @IsGranted("ROLE_USER")
 */
class ListController extends AbstractController
{
    /**
     * @Route("/{id}", name="view")
     */
    public function view(CardsList $cardsList,
        TranslatorInterface $translator,
        Request $request,
        ManagerRegistry $managerRegistry)
    {
        if ($cardsList->getUser() !== $this->getUser())
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
     * @Route(path={
     *     "en": "/{id}/edit",
     *     "fr": "/{id}/modifier"
     * }, name="edit")
     */
    public function edit(CardsList $cardsList, JWTTokenManagerInterface $JWTManager): Response
    {
        if ($cardsList->getUser() !== $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/lists/edit.html.twig', [
            'cardsList' => $cardsList,
            'token' => $JWTManager->create($this->getUser()),
        ]);
    }

    /**
     * @Route(path={
     *     "en": "/{id}/learn",
     *     "fr": "/{id}/apprendre"
     * }, name="learn")
     */
    public function learn(CardsList $cardsList, JWTTokenManagerInterface $JWTManager): Response
    {
        if ($cardsList->getUser() !== $this->getUser())
        {
            throw new NotFoundHttpException();
        }

        return $this->render('dashboard/lists/learn.html.twig', [
            'cardsList' => $cardsList,
            'token' => $JWTManager->create($this->getUser()),
        ]);
    }
}
