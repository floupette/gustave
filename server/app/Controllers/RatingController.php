<?php

namespace App\Controllers;

use App\Models\LogementModel;
use App\Models\UserModel;
use App\Models\ReservationModel;
use App\Models\RatingModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class RatingController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer un commentaire
    public function create()
    {
        $model = new RatingModel();

        // Récupérer les données du formulaire
        $data = $this->request->getJSON();

        // Récupérer les données du formulaire
        $rated = $data->rated;
        $text = $data->text;
        $logementId = $data->logement_id;
        $reservationId = $data->reservation_id;
        $userId = $data->user_id;

        // Vérifier si l'ID du logement existe en base de données
        $logementModel = new LogementModel();
        $logement = $logementModel->find($logementId);
        if (!$logement) {
            return $this->failNotFound('ID du logement non trouvé en base de données');
        }

        // Vérifier si l'ID de la réservation existe en base de données
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->find($reservationId);
        if (!$reservation) {
            return $this->failNotFound('ID de la réservation non trouvé en base de données');
        }

        // Vérifier si l'utilisateur correspond à l'auteur de la réservation
        if ($reservation['user_id'] != $userId) {
            return $this->fail('L\'utilisateur n\'est pas autorisé à laisser un commentaire pour cette réservation.', 403);
        }

        // Vérifier si le logement correspond à celui de la réservation
        if ($reservation['logement_id'] != $logementId) {
            return $this->fail('Le logement spécifié ne correspond pas à celui de la réservation.', 403);
        }

        // Vérifier si une évaluation existe déjà pour cette réservation
        $existingRating = $model->where('reservation_id', $reservationId)->first();
        if ($existingRating) {
            return $this->fail('Une évaluation existe déjà pour cette réservation.', 400);
        }

        // Créer un nouveau commentaire
        $ratingData = [
            'rated' => $rated,
            'text' => $text,
            'logement_id' => $logementId,
            'reservation_id' => $reservationId,
            'user_id' => $userId,
        ];

        // Insertion des données dans la base de données
        $model->insert($ratingData);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Le Commentaire de ' . $ratingData['rated'] . '/10 a été crée avec succès']);
    }

    // Méthode pour afficher un commentaire par ID avec les informations sur la réservation, l'utilisateur et le logement
    public function show($id)
    {
        $model = new RatingModel();

        // Récupération du commentaire par son ID
        $rating = $model->find($id);

        // Vérification si le commentaire existe
        if (!$rating) {
            return $this->failNotFound('Commentaire non trouvé');
        }

        // Ajout des informations de la réservation, de l'utilisateur et du logement
        $rating['resa'] = $model->getResa($rating['reservation_id']);
        $rating['user'] = $model->getUser($rating['user_id']);
        $rating['logement'] = $model->getLogement($rating['logement_id']);

        // Réponse avec les données du commentaire et ses informations associées
        return $this->respond($rating);
    }

    // Méthode pour afficher la liste des commentaires
    public function index()
    {
        $model = new RatingModel();

        // Récupération de tous les commentaires
        $ratings = $model->findAll();

        // Pour chaque réservation, ajouter les informations de l'utilisateur et les notes
        foreach ($ratings as &$rating) {
            $ratingId = $rating['id'];
            $rating['resa'] = $model->getResa($rating['reservation_id']);
            $rating['user'] = $model->getUser($rating['user_id']);
            $rating['logement'] = $model->getLogement($rating['logement_id']);
        }

        // Réponse avec la liste des commentaires
        return $this->respond($ratings);
    }

    // Méthode pour mettre à jour un commentaire
    public function update()
    {
        $model = new RatingModel();

        // Récupérer les données du formulaire
        $data = $this->request->getJSON();

        // Récupérer les données du formulaire
        $rated = $data->rated;
        $text = $data->text;
        $logementId = $data->logement_id;
        $reservationId = $data->reservation_id;
        $userId = $data->user_id;

        // Vérifier si l'ID du logement existe en base de données
        $logementModel = new LogementModel();
        $logement = $logementModel->find($logementId);
        if (!$logement) {
            return $this->failNotFound('ID du logement non trouvé en base de données');
        }

        // Vérifier si l'ID de la réservation existe en base de données
        $reservationModel = new ReservationModel();
        $reservation = $reservationModel->find($reservationId);
        if (!$reservation) {
            return $this->failNotFound('ID de la réservation non trouvé en base de données');
        }

        // Vérifier si l'utilisateur correspond à l'auteur de la réservation
        if ($reservation['user_id'] != $userId) {
            return $this->fail('L\'utilisateur n\'est pas autorisé à mettre à jour un commentaire pour cette réservation.', 403);
        }

        // Vérifier si le logement correspond à celui de la réservation
        if ($reservation['logement_id'] != $logementId) {
            return $this->fail('Le logement spécifié ne correspond pas à celui de la réservation.', 403);
        }

        // Vérifier si une évaluation existe déjà pour cette réservation
        $existingRating = $model->where('reservation_id', $reservationId)->first();
        if (!$existingRating) {
            return $this->fail('Il n\'y a pas encore d\'évaluation pour cette réservation.', 400);
        }

        // Mettre à jour les données de l'évaluation
        $ratingData = [
            'rated' => $rated,
            'text' => $text,
            'logement_id' => $logementId,
            'reservation_id' => $reservationId,
            'user_id' => $userId,
        ];

        $model->update($existingRating['id'], $ratingData);

        // Réponse de succès
        return $this->respondUpdated(['message' => 'Le commentaire a été mis à jour avec succès']);
    }


    // Méthode pour supprimer un commentaire par ID
    public function delete($id)
    {
        $model = new RatingModel();

        // Vérification si le commentaire existe
        if (!$model->find($id)) {
            return $this->failNotFound('Commentaire non trouvé');
        }

        // Suppression du commentaire de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Commentaire supprimé avec succès']);
    }
}
