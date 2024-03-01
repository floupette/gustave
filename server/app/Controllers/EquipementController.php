<?php

namespace App\Controllers;

use App\Models\EquipementModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class EquipementController extends Controller
{
    use ResponseTrait;

    // Méthode pour créer un équipement
    public function create()
    {
        $model = new EquipementModel();

        // Récupération des données depuis la requête HTTP POST
        $data = $this->request->getPost();

        // Insertion des données dans la base de données
        $model->insert($data);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Equipement created successfully']);
    }

    // Méthode pour afficher un équipement par ID
    public function show($id)
    {
        $model = new EquipementModel();

        // Récupération de l'équipement par son ID
        $equipement = $model->find($id);

        // Vérification si l'équipement existe
        if (!$equipement) {
            return $this->failNotFound('Equipement not found');
        }

        // Réponse avec les données de l'équipement
        return $this->respond($equipement);
    }

    // Méthode pour afficher la liste des équipements
    public function index()
    {
        $model = new EquipementModel();

        // Récupération de tous les équipements
        $equipements = $model->findAll();

        // Réponse avec la liste des équipements
        return $this->respond($equipements);
    }

    // Méthode pour mettre à jour un équipement par ID
    public function update($id)
    {
        $model = new EquipementModel();

        // Récupération des données depuis la requête HTTP PUT ou PATCH
        $data = $this->request->getRawInput();

        // Mise à jour de l'équipement dans la base de données
        $model->update($id, $data);

        // Réponse de succès
        return $this->respond(['message' => 'Equipement updated successfully']);
    }

    // Méthode pour supprimer un équipement par ID
    public function delete($id)
    {
        $model = new EquipementModel();

        // Vérification si l'équipement existe
        if (!$model->find($id)) {
            return $this->failNotFound('Equipement not found');
        }

        // Suppression de l'équipement de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Equipement deleted successfully']);
    }
}
