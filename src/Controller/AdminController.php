<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    public function admin(): Response
    {

        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
            'users' => $users
        ]);
    }

    public function users(): Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('admin/users_list.html.twig', [
            'users' => $users
        ]);
    }

    public function posts(): Response
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findBy(
            [],
            ['publicationDate' => 'DESC']
        );

        return $this->render('admin/posts_list.html.twig', [
            'posts' => $posts,
        ]);
    }
}
