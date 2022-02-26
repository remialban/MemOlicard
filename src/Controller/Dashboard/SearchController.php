<?php

namespace App\Controller\Dashboard;

use App\Repository\UserRepository;
use App\Service\TypeSense\TypeSense;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends AbstractController
{
    /**
     * @Route("/dashboard/search", name="search")
     */
    public function search(TypeSense $typeSense, Request $request, UserRepository $userRepository): Response
    {
        $response = $typeSense->search("users", $request->get("q", ""), 10, $request->get("page", 1));

        $users = [];
        foreach ($response['hits'] as $element)
        {
            $id = intval($element['document']['id']);
            $users[] = $userRepository->find($id);
        }

        return $this->render('dashboard/search/index.html.twig', [
            'users' => $users,
            'query' => $request->get("q", ""),
        ]);
    }
}
