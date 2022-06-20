<?php

namespace App\Controller;

use App\Entity\Publications;
use App\Form\PublicationsType;
use App\Repository\PublicationsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/publications', name: 'publications_')]
class PublicationsController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function index(PublicationsRepository $publicationsRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $publications = $paginator->paginate(
            $publicationsRepository->listPublication(),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('publications/index.html.twig', [
            'publications' => $publications,
        ]);
    }

    #[Route('/new', name: 'app_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PublicationsRepository $publicationsRepository): Response
    {
        $publication = new Publications();
        $form = $this->createForm(PublicationsType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publicationsRepository->add($publication);
            return $this->redirectToRoute('publications_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publications/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_show', methods: ['GET'])]
    public function show(Publications $publication): Response
    {
        return $this->render('publications/show.html.twig', [
            'publication' => $publication,
        ]);
    }

    #[Route('/{slug}/edit', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publications $publication, PublicationsRepository $publicationsRepository): Response
    {
        $form = $this->createForm(PublicationsType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publicationsRepository->add($publication);
            return $this->redirectToRoute('publications_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publications/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_delete', methods: ['POST'])]
    public function delete(Request $request, Publications $publication, PublicationsRepository $publicationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $publication->getId(), $request->request->get('_token'))) {
            $publicationsRepository->remove($publication);
        }

        return $this->redirectToRoute('publications_app_index', [], Response::HTTP_SEE_OTHER);
    }
}
