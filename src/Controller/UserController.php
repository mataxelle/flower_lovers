<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditAccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function index(User $user): Response
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'post' => $user->getPosts()
        ]);
    }

    public function edit(User $user, Request $request): Response
    {
        $form = $this->createForm(EditAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Votre compte à été modifié avec succès');

            return $this->redirectToRoute('account', ['id' => $user->getId()]);
        }

        return $this->render('user/user_edit.html.twig', [
            'editAccountForm' => $form->createView(),
            'user' => $user
        ]);
    }

    public function delete(User $user): Response
    {
        if ($user) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('message', 'Votre compte à été supprimer avec succès');

            return $this->redirectToRoute('home');
        }

        return $this->render('user/user_delete.html.twig', [
            'user' => $user
        ]);
    }
}
