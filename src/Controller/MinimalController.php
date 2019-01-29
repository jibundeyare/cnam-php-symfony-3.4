<?php
// src/Controller/MinimalController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/")
 */
class MinimalController extends AbstractController
{
    /**
     * @Route("/", name="minimal_index")
     */
    public function index(Request $request)
    {
        return $this->render('minimal/index.html.twig', [
        ]);
    }

    /**
     * @Route("/hello/{name}", name="minimal_hello_name")
     */
    public function helloName(Request $request, $name)
    {
        $greeting = "Hello {$name}!";

        return $this->render('minimal/hello-name.html.twig', [
            'greeting' => $greeting,
        ]);
    }
}
