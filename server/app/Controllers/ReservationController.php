<?php

namespace App\Controllers;

use App\Models\LogementModel;
use App\Models\UserModel;
use App\Models\ReservationModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class ReservationController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer une réservation
    public function create()
    {
 
        // Récupérer les données du formulaire JSON
        $data = $this->request->getJSON();

        
        // Valider la durée de la réservation
        $start_date = new \DateTime($data->start_date);
        $end_date = new \DateTime($data->end_date);
        $interval = $start_date->diff($end_date);
        $days_difference = $interval->days;

        if ($days_difference < 7 || $days_difference > 30) {
            return $this->fail('La durée de la réservation doit être d\'au moins une semaine et au plus un mois.', 400);
        }

        // Récupérer les données du formulaire
        $start_date = $data->start_date;
        $end_date = $data->end_date;
        $chef_cuisine = $data->chef_cuisine;
        $visite = $data->visite;
        $logementid = $data->logement_id;
        $userid = $data->user_id;

        // Créer un nouvel objet ReservationModel
        $model = new ReservationModel();

        // Vérifier si l'ID du logement existe en base de données
        $logementModel = new LogementModel();
        $logement = $logementModel->find($logementid);
        if (!$logement) {
            return $this->failNotFound('ID du logement non trouvé en base de données');
        }

        // Vérifier si l'ID de l'utilisateur existe en base de données
        $userModel = new UserModel();
        $user = $userModel->find($userid);
        if (!$user) {
            return $this->failNotFound('ID de l\'utilisateur non trouvé en base de données');
        }   

        // Vérifier si le logement est disponible pour les dates spécifiées
        $existingReservation = $model
            ->where('logement_id', $logementid)
            ->where('start_date <=', $end_date)
            ->where('end_date >=', $start_date)
            ->first();
        if ($existingReservation) {
            return $this->fail('Ce logement est déjà réservé pour cette période.', 400);
        }

        // Calcul du tarif de la réservation
        $tarif = $model->calculateReservationPrice($start_date, $end_date, $logementid);

        if ($tarif === false) {
            return $this->fail('Le logement associé à la réservation est introuvable.', 404);
        }

        // Créer un nouveau logement
        $reservationData = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'chef_cuisine' => $chef_cuisine,
            'visite' => $visite,
            'logement_id' => $logementid,
            'user_id' => $userid,
            'tarif' => $tarif,
        ];

        // Insérer la réservation dans la base de données
        $model->insert($reservationData);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Réservation créée avec succès']);
    }


    // Méthode pour afficher une réservation par ID avec les informations des utilisateurs et les notes
    public function show($id)
    {
        // Créer un nouvel objet ReservationModel
        $model = new ReservationModel();

        // Récupération de la réservation par ID
        $reservation = $model->find($id);

        if (!$reservation) {
            return $this->fail('Réservation non trouvée.', 404);
        }

        // Calculer le tarif de la réservation
        $reservation['tarif'] = $model->calculateReservationPrice($reservation['start_date'], $reservation['end_date'], $reservation['logement_id']);

        // Récupération des notes associées à la réservation
        $ratings = $model->getRating($id);

        // Récupération des informations de l'utilisateur ayant effectué la réservation
        $user = $model->getUser($reservation['user_id']);

        // Ajouter les notes et les informations de l'utilisateur à la réservation
        $reservation['ratings'] = $ratings;
        $reservation['user'] = $user;

        // Réponse avec les détails de la réservation
        return $this->respond($reservation);
    }

    // Méthode pour afficher la liste de toutes les réservations avec les informations des utilisateurs et les notes
    public function index()
    {
        // Créer un nouvel objet ReservationModel
        $model = new ReservationModel();

        // Récupération de toutes les réservations
        $reservations = $model->findAll();

        // Pour chaque réservation, ajouter les informations de l'utilisateur et les notes
        foreach ($reservations as &$reservation) {
            $reservationId = $reservation['id'];
            $ratings = $model->getRating($reservationId);
            $reservation['tarif'] = $model->calculateReservationPrice($reservation['start_date'], $reservation['end_date'], $reservation['logement_id']);
            $user = $model->getUser($reservation['user_id']);
            $reservation['ratings'] = $ratings;
            $reservation['user'] = $user;
        }

        // Réponse avec la liste des réservations
        return $this->respond($reservations);
    }

    // Méthode pour mettre à jour une réservation par ID
    public function update($id)
    {
        // Récupérer les données du formulaire JSON
        $data = $this->request->getJSON();

        // Valider la durée de la réservation
        $start_date = new \DateTime($data->start_date);
        $end_date = new \DateTime($data->end_date);
        $interval = $start_date->diff($end_date);
        $days_difference = $interval->days;

        if ($days_difference < 7 || $days_difference > 30) {
            return $this->fail('La durée de la réservation doit être d\'au moins une semaine et au plus un mois.', 400);
        }

        // Vérifier si toutes les données nécessaires sont présentes
        $requiredKeys = ['start_date', 'end_date', 'chef_cuisine', 'visite', 'logement_id', 'user_id'];
        foreach ($requiredKeys as $key) {
            if (!property_exists($data, $key)) {
                return $this->fail("La clé '$key' est manquante dans les données de la requête.", 400);
            }
        }

        // Récupérer les données du formulaire
        $start_date = $data->start_date;
        $end_date = $data->end_date;
        $chef_cuisine = $data->chef_cuisine;
        $visite = $data->visite;
        $logementid = $data->logement_id;
        $userid = $data->user_id;

        // Créer un nouvel objet ReservationModel
        $model = new ReservationModel();

        // Vérifier si la réservation existe
        $existingReservation = $model->find($id);
        if (!$existingReservation) {
            return $this->fail('Réservation non trouvée.', 404);
        }

        // Vérifier si l'ID du logement existe en base de données
        $logementModel = new LogementModel();
        $logement = $logementModel->find($logementid);
        if (!$logement) {
            return $this->failNotFound('ID du logement non trouvé en base de données');
        }

        // Vérifier si l'ID de l'utilisateur existe en base de données
        $userModel = new UserModel();
        $user = $userModel->find($userid);
        if (!$user) {
            return $this->failNotFound('ID de l\'utilisateur non trouvé en base de données');
        }

        // Vérifier si le logement est disponible pour les dates spécifiées
        $existingReservationForDates = $model
            ->where('logement_id', $logementid)
            ->where('start_date <=', $end_date)
            ->where('end_date >=', $start_date)
            ->where('id !=', $id) // exclure la réservation actuelle
            ->first();
        if ($existingReservationForDates) {
            return $this->fail('Ce logement est déjà réservé pour cette période.', 400);
        }

        // Mettre à jour la réservation dans la base de données
        $reservationData = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'chef_cuisine' => $chef_cuisine,
            'visite' => $visite,
            'logement_id' => $logementid,
            'user_id' => $userid,
        ];
        $model->update($id, $reservationData);

        // Réponse de succès
        return $this->respond(['message' => 'Réservation modifiée avec succès']);
    }

    // Méthode pour supprimer une réservation par ID
    public function delete($id)
    {
        // Créer un nouvel objet ReservationModel
        $model = new ReservationModel();

        // Vérifier si la réservation existe
        $existingReservation = $model->find($id);
        if (!$existingReservation) {
            return $this->fail('Réservation non trouvée.', 404);
        }

        // Supprimer la réservation de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Réservation supprimée avec succès']);
    }
}
