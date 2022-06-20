<?php

namespace App\Controller;

use App\Repository\PublicationsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/blog', name: 'blog_')]

class BlogController extends AbstractController
{
    ##[Route('/', name: 'app_blog')]
    /*public function index(): Response
    {
        return $this->render('', [
            'controller_name' => 'BlogController',
        ]);
    }*/

    #[Route('/', name: 'app_blog', methods: ['GET'])]
    public function index(PublicationsRepository $publicationsRepository): Response
    {
        return $this->render('blog/index.html.twig', [
            'publications' => $publicationsRepository->lastsix(),
        ]);
    }
}
