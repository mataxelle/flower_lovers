<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    public function add(): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        return $this->render('main/post_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function show()
    {
        return $this->render('main/post_show.html.twig');
    }

    public function edit()
    {
        return $this->render('main/post_edit.html.twig');
    }
}
