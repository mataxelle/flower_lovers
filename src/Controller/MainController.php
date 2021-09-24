<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    public function index(PostRepository $postRepo): Response
    {
        $posts = $postRepo->findBy(
            [],
            ['lastUpdateDate' => 'DESC']
        );

        return $this->render('main/index.html.twig', ['posts' => $posts]);
    }

    public function add(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());

            $post->setLastUpdateDate(new \DateTime());  //initialisation de la dernière modification

            if ($post->getPicture() !== null) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'), // Le dossier dans le quel le fichier va être charger (services.yaml)
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $post->setPicture($fileName);
            }

            if (!$post->getPublicationDate()) {
                $post->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager(); // On récupère l'entity manager
            $em->persist($post); // On confie notre entité à l'entity manager (on persist l'entité)
            $em->flush(); // On execute la requete

            $this->addFlash('message', 'Nouveau post ajouté avec succès');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('main/post_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show(Post $post)
    { 
        return $this->render('main/post_show.html.twig', [
            'post' => $post,
            'user' => $post->getUser()
        ]);
    }

    public function edit(Post $post, Request $request)
    {
        $oldPicture = $post->getPicture();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());

            $post->setLastUpdateDate(new \DateTime());

            if ($post->getPicture() !== null && $post->getPicture() !== $oldPicture) {
                $file = $form->get('picture')->getData();
                $fileName =  uniqid(). '.' .$file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $post->setPicture($fileName);
            } else {
                $post->setPicture($oldPicture);
            }

            if (!$post->getPublicationDate()) {
                $post->setPublicationDate(new \DateTime());
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $this->addFlash('message', 'Post modifier avec succès');

            return $this->redirectToRoute('post_show', ['id' => $post->getId()]);
        }

        return $this->render('main/post_edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    public function delete(Post $post): Response 
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($post);
        $em->flush();

        $this->addFlash('message', 'Post supprimé avec succès');

        return $this->redirectToRoute('home');
    }

    public function about(): Response
    { 
        return $this->render('main/about.html.twig');
    }
}
