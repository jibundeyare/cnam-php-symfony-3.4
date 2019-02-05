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

    /**
     * @Route("/{numcat}/")
     */
    public function edit(Request $request, $numcat)
    {
        $errors = [];
        $categorie = [];

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            // validation des données

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (!$request->request->has('libelle') || empty($request->request->get('libelle'))) {
                // le champ n'a pas été transmis ou il est vide
                $errors['libelle'] = 'Veuillez renseigner ce champ';
            } else {
                // le champ a pas été transmis et il n'est pas vide
                $categorie['libelle'] = $request->request->get('libelle');
            }

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (!$request->request->has('tauxtva') || empty($request->request->get('tauxtva'))) {
                // le champ n'a pas été transmis ou il est vide
                $errors['tauxtva'] = 'Veuillez renseigner ce champ';
            } else {
                // le champ a pas été transmis et il n'est pas vide
                $categorie['tauxtva'] = $request->request->get('tauxtva');
            }

            if (!$errors) {
                // il n'y a pas d'erreur, on peut mettre la BDD à jour

                $count = $this->conn->update('categorie', [
                    'libelle' => $categorie['libelle'],
                    'tauxtva' => $categorie['tauxtva'],
                ], ['numcat' => $numcat]);
            }
        }

        $categorie = $this->conn->fetchAssoc('SELECT * FROM `categorie` WHERE `numcat` = :numcat', [
            'numcat' => $numcat,
        ]);

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
