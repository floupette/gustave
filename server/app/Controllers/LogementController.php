<?php

namespace App\Controllers;

use App\Models\LogementModel;
use App\Models\LogementEquipementModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class LogementController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer un logement avec ses équipements
    public function create()
    {
        $model = new LogementModel();

        // Récupération des données depuis la requête HTTP POST
        $data = $this->request->getPost();

        // Insertion des données dans la base de données
        $model->insert($data);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Logement created successfully']);
    }

    // Méthode pour afficher un logement par ID avec ses équipements
    public function show($id)
    {
        $model = new LogementModel();

        // Récupération du logement par son ID avec les équipements associés
        $logement = $model->with('equipements')->find($id);

        // Vérification si le logement existe
        if (!$logement) {
            return $this->failNotFound('Logement not found');
        }

        // Réponse avec les données du logement
        return $this->respond($logement);
    }

    // Méthode pour afficher la liste des logements avec leurs équipements
    public function index()
    {
        $model = new LogementModel();

        // Récupération de tous les logements avec leurs équipements associés
        $logements = $model->with('equipements')->findAll();

        // Réponse avec la liste des logements
        return $this->respond($logements);
    }

    // Méthode pour mettre à jour un logement par ID avec ses équipements
    public function update($id)
    {
        $model = new LogementModel();

        // Récupération des données depuis la requête HTTP PUT ou PATCH
        $data = $this->request->getRawInput();

        // Mise à jour du logement dans la base de données
        $model->update($id, $data);

        // Réponse de succès
        return $this->respond(['message' => 'Logement updated successfully']);
    }

    // Méthode pour supprimer un logement par ID
    public function delete($id)
    {
        $model = new LogementModel();

        // Vérification si le logement existe
        if (!$model->find($id)) {
            return $this->failNotFound('Logement not found');
        }

        // Suppression du logement de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Logement deleted successfully']);
    }
}