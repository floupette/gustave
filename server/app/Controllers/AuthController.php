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
        $data = $this->request->getJSON(true);
        $username = $data['email'];
        $password = $data['password'];

        //var_dump($data);

        $user = $userModel->where('email', $username)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie, démarrez la session et redirigez l'utilisateur
            session()->set('isLoggedIn', true);
            session()->set('email', $user['email']);
            // Supprimer le champ de mot de passe avant de renvoyer les informations
            unset($user['password']);
            // Retournez les données souhaitées sous forme de JSON
            $response = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'message' => $user['name'] . ' est connecté'
            ];
            return $this->response->setJSON($response);
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
    
        // Vérification si l'utilisateur existe
        if (!$user) {
            return $this->failNotFound('Utilisateur non trouvé.');
        }
    
        // Récupération des réservations et des évaluations de l'utilisateur
        $userId = $user['id'];
        $reservations = $userModel->getReservation($userId);
        $ratings = $userModel->getRating($userId);
        $user['reservations'] = $reservations;
        $user['ratings'] = $ratings;
    
        // Supprimer le champ de mot de passe avant de renvoyer les informations
        unset($user['password']);
    
        // Répondre avec les informations de l'utilisateur connecté, y compris ses réservations et évaluations
        return $this->respond($user);
    }
    

    public function logout()
    {
        // Supprimer le cookie de session
        $this->response->setCookie('ci_session', '', time() - 3600, '/');

        // Déconnectez l'utilisateur en détruisant la session
        $session = session();
        $session->destroy();
 
        // Réponse de succès
        return $this->respond(['message' => 'Déconnexion Réussie']);
    }
}
