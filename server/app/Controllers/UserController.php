<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class UserController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer un utilisateur
    public function create()
    {

        // Récupérer les données du formulaire
        $data = $this->request->getJSON();

        // Récupérer les données du formulaire
        $email = $data->email;
        $name = $data->name;
        $tel = $data->tel;
        $isAdmin = $data->is_admin;

        // Vérifier si l'utilisateur existe déjà
        $userModel = new UserModel();
        $existingUser = $userModel->where('email', $email)->first();

        if ($existingUser) {
            // Afficher un message d'erreur si l'utilisateur existe déjà
            return $this->failNotFound($name . ' existe déjà');
        } else {

            // Générer un mot de passe aléatoire si non fourni
            if (!isset($password)) {
                $password = $this->generateRandomPassword();
            }

            // Envoyer un e-mail au nouvel utilisateur avec le mot de passe généré
            $this->sendWelcomeEmail($email, $password);

            // Hasher le mot de passe
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Créer un nouvel utilisateur
            $userData = [
                'email' => $email,
                'name' => $name,
                'tel' => $tel,
                'password' => $hashedPassword,
                'is_admin' => $isAdmin
            ];
        
            // Insérer l'utilisateur dans la base de données
            $userModel->insert($userData);
        }

        // Réponse de succès
        return $this->respondCreated(['message' => $name . ' crée avec succès']);
    }

    // Méthode pour envoyer un e-mail de bienvenue avec le mot de passe généré
    private function sendWelcomeEmail($email, $password)
    {
        $to = $email;
        $subject = 'Bienvenue Chez Gustave';
        $message = "Bienvenue dans notre application ! \n\n Vous avez été invité par votre parrain ! \n\n Votre mot de passe temporaire est : $password";

        // Adresse e-mail de l'expéditeur à partir de la configuration
        $fromEmail = config('Email')->fromEmail;
        $fromName = config('Email')->fromName;

        // En-têtes de l'e-mail
        $headers = 'From: ' . $fromName . ' <' . $fromEmail . '>' . "\r\n";

        // Envoyer l'e-mail
        mail($to, $subject, $message, $headers);
    }


    // Méthode pour afficher un utilisateur par ID
    public function show($id)
    {
        $model = new UserModel();

        // Récupération de l'utilisateur par son ID
        $user = $model->find($id);

        // Vérification si l'utilisateur existe
        if (!$user) {
            return $this->failNotFound($user['name'] . ' non trouvé');
        }

        // Suppression du mot de passe de la réponse
        unset($user['password']);

        // Réponse avec les données de l'utilisateur
        return $this->respond($user);
    }

    // Méthode pour afficher la liste des utilisateurs
    public function index()
    {
        $model = new UserModel();

        // Récupération de tous les utilisateurs
        $users = $model->findAll();

        // Suppression des mots de passe de la réponse
        foreach ($users as &$user) {
            unset($user['password']);
        }

        // Réponse avec la liste des utilisateurs
        return $this->respond($users);
    }

    // Méthode pour mettre à jour un utilisateur par ID
    public function update($id)
    {
        $model = new UserModel();

        // Récupération de l'utilisateur par son ID
        $user = $model->find($id);

        // Vérification si l'utilisateur existe
        if (!$user) {
            return $this->failNotFound('Utilisateur non trouvé');
        }

        // Récupération des données de la requête PUT
        $data = $this->request->getJSON(true); // Récupération des données sous forme de tableau associatif

        // Assurez-vous que l'e-mail est inclus dans les données et qu'il est valide
        if (!isset($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return $this->fail('L\'e-mail est requis et doit être valide.', 400);
        }

        // Mettre à jour les autres champs si disponibles
        $user['name'] = isset($data['name']) ? $data['name'] : $user['name'];
        $user['tel'] = isset($data['tel']) ? $data['tel'] : $user['tel'];
        $user['is_admin'] = isset($data['is_admin']) ? $data['is_admin'] : $user['is_admin'];
        
        // Vérifier si le mot de passe est fourni et s'il est différent du mot de passe actuel
        if (isset($data['password']) && $data['password'] !== $user['password']) {
            $user['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Mettre à jour l'utilisateur dans la base de données
        $model->update($id, $user);

        // Réponse de succès
        return $this->respond(['message' => $user['name'] . ' modifié avec succès']);
    }


    // Méthode pour supprimer un utilisateur par ID
    public function delete($id)
    {
        $model = new UserModel();

        // Récupération de l'utilisateur par son ID
        $user = $model->find($id);

        // Vérification si l'utilisateur existe
        if (!$user) {
            return $this->failNotFound($user['name'] . ' non trouvé');
        }

        // Suppression de l'utilisateur de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => $user['name'] . ' supprimé avec succès']);
    }

    // Méthode pour génèrer un mot de passe aléatoire en mélangeant une chaîne de caractères alphanumériques de référence
    private function generateRandomPassword($length = 10)
    {
        return substr( // Extrait une sous-chaîne de caractères de la chaîne générée
            str_shuffle( // Mélange aléatoirement les caractères de la chaîne générée
                str_repeat( // Répète une chaîne de caractères pour atteindre la longueur souhaitée
                    $x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', // Chaîne de caractères alphanumériques de référence
                    ceil($length/strlen($x)) // Répète la chaîne de caractères pour atteindre ou dépasser la longueur souhaitée du mot de passe
                )
            ), 
            1, // Commence l'extraction à partir du deuxième caractère de la chaîne (caractère à l'index 0)
            $length // Extrait une sous-chaîne de longueur $length à partir de la chaîne mélangée
        );
    }
}
