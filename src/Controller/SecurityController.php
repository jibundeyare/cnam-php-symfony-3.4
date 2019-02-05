<?php
// src/Controller/SecurityController.php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\Method;

/**
 * @Route("/")
 */
class SecurityController extends AbstractController
{
    private $conn;

    public function __construct(Connection $conn, SessionInterface $session)
    {
        $this->conn = $conn;
        $this->session = $session;
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(Request $request)
    {
        $errors = [];

        // valeurs par défaut du formulaire de connexion
        $formData = [
            'login' => null,
            'password' => null,
        ];

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            // récupération des données envoyées par l'utilisateur
            $formData['login'] = $request->request->get('login');
            $formData['password'] = $request->request->get('password');

            // validation des données

            // recherche du compte utilisateur dans la BDD
            $user = $this->conn->fetchAssoc('SELECT * FROM `user` WHERE `login` = :login', [
                'login' => $formData['login'],
            ]);

            // vérification du mot de passe
            if (password_verify($formData['password'], $user['password_hash'])) {
                // le mot de passe est correct

                // @todo stocker les données du compte utilisateur dans la variable de session

                // redirection vers une autre page
                return $this->redirectToRoute('security_secured');
            } else {
                // le mot de passe est incorrect
                $errors['login'] = 'Votre login ou votre mot de passe est incorrect';
            }
        }

        // affichage du rendu d'un template
        return $this->render('security/login.html.twig', [
            // transmission de données au template
            'errors' => $errors,
            'formData' => $formData,
        ]);
    }

    /**
     * @Route("/register", name="security_register")
     */
    public function register(Request $request)
    {
        $errors = [];

        // valeurs par défaut du compte utilisateur
        $user = [
            'login' => null,
            'password' => null,
            'email' => null,
        ];

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            // récupération des données envoyées par l'utilisateur
            $user['login'] = $request->request->get('login');
            $user['password'] = $request->request->get('password');
            $user['email'] = $request->request->get('email');

            // validation des données

            // recherche d'un compte utilisateur ayant le même login dans la BDD
            $userWithSameLogin = $this->conn->fetchAssoc('SELECT * FROM `user` WHERE `login` = :login', [
                'login' => $user['login'],
            ]);

            if (empty($request->request->get('login'))) {
                $errors['login'] = 'Veuillez renseigner ce champ';
            } else if ($userWithSameLogin) {
                $errors['login'] = 'Ce login est déjà utilisé';
            }

            if (empty($request->request->get('password'))) {
                $errors['password'] = 'Veuillez renseigner ce champ';
            }

            if (empty($request->request->get('email'))) {
                $errors['email'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                // le mot de passe n'est pas stocké en BDD, seul le hash du mot de passe est stocké
                // création du hash à partir du mot de passe
                $hash = password_hash($user['password'], PASSWORD_DEFAULT);

                // exécution de la requête et récupération du nombre de lignes affectées dans la variable `$count`
                $count = $this->conn->insert('user', [
                    'login' => $user['login'],
                    'email' => $user['email'],
                    'password_hash' => $hash,
                ]);

                // récupération de l'id de la dernière ligne créée par la BDD dans la variable `$lastInsertId`
                $lastInsertId = $this->conn->lastInsertId();

                // redirection vers une autre page
                return $this->redirectToRoute('security_login');
            }
        }

        // affichage du rendu d'un template
        return $this->render('security/register.html.twig', [
            // transmission de données au template
            'errors' => $errors,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/profile", name="security_profile")
     */
    public function profile(Request $request)
    {
        // @todo filtrer les utilisateurs avec la session
        // @todo récupération des données de l'utilisateur depuis la variable de session
        $user = null;

        // récupération des données du compte utilisateur
        $user = $this->conn->fetchAssoc('SELECT * FROM `user` WHERE `id` = :id', [
            'id' => $user['id'],
        ]);

        $errors = [];

        // y a-t-il des données envoyées en POST
        if ($request->getMethod() == 'POST') {
            // récupération des données envoyées par l'utilisateur
            $user['password'] = $request->request->get('password');
            $user['email'] = $request->request->get('email');

            // validation des données

            // @info le login n'est pas modifiable

            // vérification que le champ n'est pas vide
            if (empty($request->request->get('password'))) {
                $errors['password'] = 'Veuillez renseigner ce champ';
            }

            // vérification que le champ n'est pas vide
            if (empty($request->request->get('email'))) {
                $errors['email'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                // création du hash à partir du mot de passe
                $hash = password_hash($user['password'], PASSWORD_DEFAULT);

                // exécution de la requête et récupération du nombre de lignes affectées dans la variable `$count`
                $count = $this->conn->update('user', [
                    'email' => $user['email'],
                    'password_hash' => $hash,
                ], ['id' => $user['id']]);

                // récupération de l'id de la dernière ligne créée par la BDD dans la variable `$lastInsertId`
                $lastInsertId = $this->conn->lastInsertId();

                // @todo ajouter une notification dans un flashbag
            }
        }

        // le mot de passe n'est pas stocké en BDD et ne doit pas être affiché
        $user['password'] = null;

        // affichage du rendu d'un template
        return $this->render('security/profile.html.twig', [
            // transmission de données au template
            'errors' => $errors,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(Request $request)
    {
        // @todo filtrer les utilisateurs avec la session

        // @todo supprimer toutes les données de la variable de session avec clear()

        // redirection vers la page login
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/secured", name="security_secured")
     */
    public function secured(Request $request)
    {
        // @todo filtrer les utilisateurs avec la session
        // @todo récupération des données de l'utilisateur depuis la variable de session
        $user = null;

        // affichage du rendu d'un template
        return $this->render('security/secured.html.twig', [
            // transmission de données au template
            'user' => $user,
        ]);
    }
}
