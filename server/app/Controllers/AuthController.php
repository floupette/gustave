<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class AuthController extends Controller
{

    use ResponseTrait;

    public function login()
    {
        // Vérifiez si les informations de connexion sont valides
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie, démarrez la session et redirigez l'utilisateur
            session()->set('isLoggedIn', true);
            session()->set('email', $user['email']);
            
            // Utilisateur authentifié, retourner les détails de l'utilisateur
            unset($user['password']); // Ne pas renvoyer le mot de passe dans la réponse
            return $this->respond($user);
            //return redirect()->to('/auth/login'); Voir pour la redirection
        } else {
            // Authentification échouée, affichez un message d'erreur
            return $this->failUnauthorized("Nom d'utilisateur ou mot de passe incorrect");
        }
    }

    public function info()
    {
        // Vérifie si l'utilisateur est connecté
        if (!session()->get('isLoggedIn')) {
            // Si l'utilisateur n'est pas connecté, renvoyer une erreur non autorisée
            return $this->failUnauthorized('Vous n\'êtes pas connecté.');
        }

        // Récupérer les informations de l'utilisateur connecté
        $email = session()->get('email');
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Supprimer le champ de mot de passe avant de renvoyer les informations
        unset($user['password']);

        // Répondre avec les informations de l'utilisateur connecté
        return $this->respond($user);
    }

    public function logout()
    {
        // Déconnectez l'utilisateur en détruisant la session
        $session = session();
        $session->destroy();
 
        // Réponse de succès
        return $this->respond(['message' => 'Déconnexion Réussie']);
    }
}
