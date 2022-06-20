<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\UsersType;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/users', name: 'users_')]
class UsersController extends AbstractController
{
    #[Route('/', name: 'app_profile', methods: ['GET'])]
    public function profil(UsersRepository $usersRepository): Response
    {
        return $this->render('users/profile.html.twig', [
            'users' => $usersRepository->findAll(),
        ]);
    }

    #[Route('/index', name: 'app_index', methods: ['GET'])]
    public function index(UsersRepository $usersRepository): Response
    {
        return $this->render('users/index.html.twig', [
            'users' => $usersRepository->findbyId(),
        ]);
    }

    #[Route('/new', name: 'app_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UsersRepository $usersRepository): Response
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepository->add($user);
            return $this->redirectToRoute('users_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_show', methods: ['GET'])]
    public function show(Users $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepository->add($user);
            return $this->redirectToRoute('users_app_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_delete', methods: ['POST'])]
    public function delete(Request $request, Users $user, UsersRepository $usersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $usersRepository->remove($user);
        }

        return $this->redirectToRoute('users_app_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function editprofile(Request $request, UsersRepository $usersRepos): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersRepos->add($user);
            $this->addFlash('message', 'Profil mis à jour');
            return $this->redirectToRoute('users_app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('users/editProfile.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/pass/edit', name: 'app_pass_edit', methods: ['GET', 'POST'])]
    public function editpass(Request $request, UsersRepository $usersRepos, UserPasswordHasherInterface $passwordEncoder): Response
    {
        if ($request->isMethod('POST')) {
            $user = $this->getUser();
            // ovérifie si les 2 mots de passe sont identiques
            if ($request->get('pass') == $request->get('pass2')) {
                $user->setPassword($passwordEncoder->hashPassword($user, $request->get('pass')));
                $usersRepos->add($user);
                $this->addFlash('message', 'Mot de pas a étémis à jour');
                return $this->redirectToRoute('users_app_profile', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', 'les deux (2) mots de passe ne sont pas identiques');
            }
        }


        return $this->renderForm('users/editPass.html.twig');
    }
}
