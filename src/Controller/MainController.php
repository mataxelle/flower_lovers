<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    public function add()
    {
        return $this->render('main/post_add.html.twig');
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
