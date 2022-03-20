<?php

namespace App\Controller;

use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    /**
     * @Route("/{slug}", name="page", priority=-100)
     */
    public function page(PageRepository $pageRepository, Request $request, $slug): Response
    {
        $page = $pageRepository->findOneBy([
            'slug' => $slug,
            'locale' => $request->getLocale(),
        ]);

        if (!$page)
        {
            throw $this->createNotFoundException();
        }

        return $this->render('pages/single.html.twig', [
            'page' => $page,
        ]);
    }
}
