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

        // envoi d'une requête SQL à la BDD et récupération du résultat sous forme d'un tableau de tableaux PHP dans la variable `$categories`
        $categories = $this->conn->fetchAll($sql);

        // affichage du rendu d'un template
        return $this->render('categorie/index.html.twig', [
            // transmission de données au template
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{numcat}/", name="categorie_show", requirements={"numcat"="\d+"})
     */
    public function show(Request $request, $numcat)
    {
        // envoi d'une requête SQL à la BDD et récupération du résultat sous forme d'un' tableau PHP dans la variable `$categorie`
        $categorie = $this->conn->fetchAssoc('SELECT * FROM `categorie` WHERE `numcat` = :numcat', [
            'numcat' => $numcat,
        ]);

        // affichage du rendu d'un template
        return $this->render('categorie/show.html.twig', [
            // transmission de données au template
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/new/", name="categorie_new")
     */
    public function new(Request $request)
    {
        $errors = [];

        $categorie = [
            'numcat' => null,
            'libelle' => null,
            'tauxtva' => null,
        ];

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            $categorie['libelle'] = $request->request->get('libelle');
            $categorie['tauxtva'] = $request->request->get('tauxtva');

            // validation des données

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (empty($categorie['libelle'])) {
                // le champ n'a pas été transmis ou il est vide
                $errors['libelle'] = 'Veuillez renseigner ce champ';
            }

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (empty($categorie['tauxtva'])) {
                // le champ n'a pas été transmis ou il est vide
                $errors['tauxtva'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                // il n'y a pas d'erreur, on peut mettre la BDD à jour

                // exécution de la requête et récupération du nombre de lignes affectées dans la variable `$count`
                $count = $this->conn->insert('categorie', [
                    'libelle' => $categorie['libelle'],
                    'tauxtva' => $categorie['tauxtva'],
                ]);

                // récupération de l'id de la dernière ligne créée par la BDD dans la variable `$lastInsertId`
                $lastInsertId = $this->conn->lastInsertId();

                // redirection vers la page de visualisation
                return $this->redirectToRoute('categorie_show', [
                    'numcat' => $lastInsertId
                ]);
            }
        }

        // affichage du rendu d'un template
        return $this->render('categorie/new.html.twig', [
            // transmission de données au template
            'errors' => $errors,
            'categorie' => $categorie,
        ]);
    }

    // @todo créer une action suppression (seulement moi)

    /**
     * @Route("/{numcat}/edit", name="categorie_edit", requirements={"numcat"="\d+"})
     */
    public function edit(Request $request, $numcat)
    {
        $errors = [];

        $categorie = $this->conn->fetchAssoc('SELECT * FROM `categorie` WHERE `numcat` = :numcat', [
            'numcat' => $numcat,
        ]);

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            $categorie['libelle'] = $request->request->get('libelle');
            $categorie['tauxtva'] = $request->request->get('tauxtva');

            // validation des données

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (empty($categorie['libelle'])) {
                // le champ n'a pas été transmis ou il est vide
                $errors['libelle'] = 'Veuillez renseigner ce champ';
            }

            // vérification si le champ a bien été transmis et qu'il n'est pas vide
            if (empty($categorie['tauxtva'])) {
                // le champ n'a pas été transmis ou il est vide
                $errors['tauxtva'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                // il n'y a pas d'erreur, on peut mettre la BDD à jour

                $count = $this->conn->update('categorie', [
                    'libelle' => $categorie['libelle'],
                    'tauxtva' => $categorie['tauxtva'],
                ], ['numcat' => $numcat]);
            }
        }

        // affichage du rendu d'un template
        return $this->render('categorie/edit.html.twig', [
            // transmission de données au template
            'errors' => $errors,
            'categorie' => $categorie,
        ]);
    }
}
