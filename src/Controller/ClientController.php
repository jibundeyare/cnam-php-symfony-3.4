<?php
// src/Controller/ClientController.php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{
    private $conn;
    private $session;

    public function __construct(Connection $conn, SessionInterface $session)
    {
        $this->conn = $conn;
        $this->session = $session;
    }

    /**
     * @Route("/", name="client_index")
     */
    public function index(Request $request)
    {
        // filtrer les utilisateurs avec la session
        if (!$this->session->get('user')) {
            return $this->redirectToRoute('security_login');
        }

        // votre code ici
    }

    /**
     * @Route("/{numclient}/", name="client_show")
     */
    public function show(Request $request, $numclient)
    {
        // filtrer les utilisateurs avec la session
        if (!$this->session->get('user')) {
            return $this->redirectToRoute('security_login');
        }

        // votre code ici
    }
}
