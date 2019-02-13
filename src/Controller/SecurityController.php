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
    private $session;

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

                // stocker les données du compte utilisateur dans la variable de session
                $this->session->set('user', $user);

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

            if (empty($user['login'])) {
                $errors['login'] = 'Veuillez renseigner ce champ';
            } elseif (strlen($user['login']) < 4) {
                $errors['login'] = 'Veuillez renseigner un identifiant de 4 caractères minimum';
            } else if ($userWithSameLogin) {
                $errors['login'] = 'Cet identifiant est déjà utilisé';
            }

            if (empty($user['password'])) {
                $errors['password'] = 'Veuillez renseigner ce champ';
            } elseif (strlen($user['password']) < 8) {
                $errors['password'] = 'Veuillez renseigner un mot de passe de 8 caractères minimum';
            }

            if (empty($user['email'])) {
                $errors['email'] = 'Veuillez renseigner ce champ';
            } elseif (filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Veuillez renseigner un email valide';
            }

            if (!$errors) {
                // le mot de passe n'est pas stocké en BDD, seul le hash du mot de passe est stocké
                // création du hash à partir du mot de passe
                $user['password_hash'] = password_hash($user['password'], PASSWORD_DEFAULT);

                // exécution de la requête et récupération du nombre de lignes affectées dans la variable `$count`
                $count = $this->conn->insert('user', [
                    'login' => $user['login'],
                    'email' => $user['email'],
                    'password_hash' => $user['password_hash'],
                ]);

                // récupération de l'id de la dernière ligne créée par la BDD dans la variable `$lastInsertId`
                $lastInsertId = $this->conn->lastInsertId();

                // redirection vers une autre page
                return $this->redirectToRoute('security_login');
            }
        }

        // le mot de passe n'est pas stocké en BDD et ne doit pas être affiché
        $user['password'] = null;

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
        // filtrer les utilisateurs avec la session
        if (!$this->session->get('user')) {
            return $this->redirectToRoute('security_login');
        }

        // récupération des données de l'utilisateur depuis la variable de session
        $user = $this->session->get('user');

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
            if (empty($user['password'])) {
                $errors['password'] = 'Veuillez renseigner ce champ';
            }

            // vérification que le champ n'est pas vide
            if (empty($user['email'])) {
                $errors['email'] = 'Veuillez renseigner ce champ';
            }

            if (!$errors) {
                // création du hash à partir du mot de passe
                $user['password_hash'] = password_hash($user['password'], PASSWORD_DEFAULT);

                // exécution de la requête et récupération du nombre de lignes affectées dans la variable `$count`
                $count = $this->conn->update('user', [
                    'email' => $user['email'],
                    'password_hash' => $user['password_hash'],
                ], ['id' => $user['id']]);

                // récupération de l'id de la dernière ligne créée par la BDD dans la variable `$lastInsertId`
                $lastInsertId = $this->conn->lastInsertId();

                // mise à jour de la variable de session
                $this->session->set('user', $user);

                // ajouter un message flash
                $this->addFlash(
                    'notice',
                    'Vos changements ont été enregistrés'
                );
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
        // filtrer les utilisateurs avec la session
        if ($this->session->get('user')) {
            // supprimer toutes les données de la variable de session
            $this->session->clear();
        }

        // redirection vers la page login
        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/secured", name="security_secured")
     */
    public function secured(Request $request)
    {
        // filtrer les utilisateurs avec la session
        if (!$this->session->get('user')) {
            return $this->redirectToRoute('security_login');
        }

        // récupération des données de l'utilisateur depuis la variable de session
        $user = $this->session->get('user');

        // affichage du rendu d'un template
        return $this->render('security/secured.html.twig', [
            // transmission de données au template
            'user' => $user,
        ]);
    }
}
