<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function blog(
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        Request $request)
    {
        $posts = $paginator->paginate(
            $postRepository->findAll(),
            $request->query->get('page', 1),
            5
        );

        return $this->render('blog/list.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/blog/{slug}", name="blog_view")
     */
    public function readPost($slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBySlug($slug);
        if (!$post)
        {
            throw $this->createNotFoundException("This post doesn't exist");
        }

        return $this->render('blog/single.html.twig', [
            'post' => $post,
        ]);
    }
}
