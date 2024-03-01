<?php

namespace App\Controllers;

use App\Models\ReservationModel;
use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class ReservationController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer une réservation
    public function create()
    {
        $model = new ReservationModel();

        // Récupération des données depuis la requête HTTP POST
        $data = $this->request->getPost();

        // Insertion des données dans la base de données
        $model->insert($data);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Reservation créée avec succès']);
    }

    // Méthode pour afficher une réservation par ID
    public function show($id)
    {
        $model = new ReservationModel();

        // Récupération de la réservation par son ID
        $reservation = $model->find($id);

        // Vérification si la réservation existe
        if (!$reservation) {
            return $this->failNotFound('Reservation non trouvée');
        }

        // Réponse avec les données de la réservation
        return $this->respond($reservation);
    }

    // Méthode pour afficher la liste des réservations
    public function index()
    {
        $model = new ReservationModel();

        // Récupération de toutes les réservations
        $reservations = $model->findAll();

        // Réponse avec la liste des réservations
        return $this->respond($reservations);
    }

    // Méthode pour mettre à jour une réservation par ID
    public function update($id)
    {
        $model = new ReservationModel();

        // Récupération des données depuis la requête HTTP PUT ou PATCH
        $data = $this->request->getRawInput();

        // Mise à jour de la réservation dans la base de données
        $model->update($id, $data);

        // Réponse de succès
        return $this->respond(['message' => 'Reservation modifiée avec succès']);
    }

    // Méthode pour supprimer une réservation par ID
    public function delete($id)
    {
        $model = new ReservationModel();

        // Vérification si la réservation existe
        if (!$model->find($id)) {
            return $this->failNotFound('Reservation non trouvée');
        }

        // Suppression de la réservation de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Reservation supprimée avec succès']);
    }
}
