<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        // Vérifier si l'e-mail ou le numéro de téléphone est déjà utilisé
        $userModel = new UserModel();
        $existingUser = $userModel->where('email', $email)->first();
        $existingTel = $userModel->where('tel', $tel)->first();

        if ($existingUser) {
            return $this->fail('L\'adresse e-mail est déjà utilisée.', 400);
        } elseif ($existingTel) {
            return $this->fail('Le numéro de téléphone est déjà utilisé.', 400);
        } else {
            // Générer un mot de passe aléatoire si non fourni
            $password = $this->generateRandomPassword();

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
        return $this->respondCreated(['message' => $name . ' créé avec succès']);
    }

    // Méthode pour envoyer un e-mail de bienvenue avec le mot de passe généré
    private function sendWelcomeEmail($email, $password)
    {
        // Création d'une nouvelle instance de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Paramètres SMTP
            $mail->isSMTP();
            $mail->Host = config('Email')->SMTPHost;
            $mail->SMTPAuth = true;
            $mail->Username = config('Email')->SMTPUser;
            $mail->Password = config('Email')->SMTPPass;
            $mail->SMTPSecure = config('Email')->SMTPCrypto;
            $mail->Port = config('Email')->SMTPPort;

            // Destinataire
            $mail->setFrom(config('Email')->fromEmail, config('Email')->fromName);
            $mail->addAddress($email);

            // Contenu de l'e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue Chez Gustave';
            $mail->Body = "Bienvenue dans notre application !<br><br>Vous avez été invité par votre parrain !<br><br>Votre mot de passe temporaire est : $password";

            // Envoi de l'e-mail
            $mail->send();
            echo 'Le Mail a été envoyé';
        } catch (Exception $e) {
            echo "Le Mail n'a pas été envoyé. Erreur: {$mail->ErrorInfo}";
        }
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

        // Récupération des réservations de l'utilisateur
        $reservations = $model->getReservation($id);
        
        // Récupération des ratings de l'utilisateur
        $ratings = $model->getRating($id);

        // Ajout des réservations et des ratings à la réponse de l'utilisateur
        $user['reservations'] = $reservations;
        $user['ratings'] = $ratings;

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
            $userId = $user['id'];
            $reservations = $model->getReservation($userId);
            $ratings = $model->getRating($userId);
            $user['reservations'] = $reservations;
            $user['ratings'] = $ratings;
            // Suppression du mot de passe de la réponse
            unset($user['password']);
        }

        // Réponse avec la liste des utilisateurs
        return $this->respond($users);
    }

    // Méthode pour mettre à jour un utilisateur par ID
    public function update($id)
    {
        // Récupérer les données du formulaire JSON
        $data = $this->request->getJSON();
    
        // Vérifier si l'utilisateur existe
        $userModel = new UserModel();
        $existingUser = $userModel->find($id);
        if (!$existingUser) {
            return $this->fail('Utilisateur non trouvé.', 404);
        }
    
        // Vérifier si l'e-mail ou le numéro de téléphone est déjà utilisé
        $email = $data->email;
        $tel = $data->tel;
        $existingEmail = $userModel->where('email', $email)->where('id !=', $id)->first();
        $existingTel = $userModel->where('tel', $tel)->where('id !=', $id)->first();
        if ($existingEmail) {
            return $this->fail('L\'adresse e-mail est déjà utilisée.', 400);
        } elseif ($existingTel) {
            return $this->fail('Le numéro de téléphone est déjà utilisé.', 400);
        }
    
        // Mettre à jour les données de l'utilisateur
        $userData = [
            'email' => $data->email,
            'name' => $data->name,
            'tel' => $data->tel,
            'is_admin' => $data->is_admin,
            'password' => password_hash($data->password, PASSWORD_DEFAULT)
        ];

        //dd($userData);

        // Mettre à jour l'utilisateur dans la base de données
        $userModel->update($id, $userData);
    
        // Réponse de succès
        return $this->respond(['message' => $userData['name'] . ' modifié avec succès']);
    }
    

    // Méthode pour supprimer un utilisateur par ID
    public function delete($id)
    {
        $model = new UserModel();

        // Récupération de l'utilisateur par son ID
        $user = $model->find($id);

        // Vérification si l'utilisateur existe
        if (!$user) {
            return $this->failNotFound('Utilisateur non trouvé');
        }

        // Suppression de l'utilisateur de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Utilisateur supprimé avec succès']);
    }

    // Méthode pour générer un mot de passe aléatoire
    private function generateRandomPassword($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }
}
