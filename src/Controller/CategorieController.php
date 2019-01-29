<?php
// src/Controller/CategorieController.php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    private $conn;

    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @Route("/", name="categorie_index")
     */
    public function index(Request $request)
    {
        $sql = "SELECT * FROM categorie";

        // envoi d'une requête SQL à la BDD et récupération du résultat sous forme de tableau PHP dans la variable `$items`
        $categories = $this->conn->fetchAll($sql);

        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}