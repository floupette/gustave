<?php

namespace App\Controllers;

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

        // Récupération des données depuis la requête HTTP POST
        $data = $this->request->getPost();

        // Insertion des données dans la base de données
        $model->insert($data);

        // Réponse de succès
        return $this->respondCreated(['message' => 'Rating created successfully']);
    }

    // Méthode pour afficher un commentaire par ID
    public function show($id)
    {
        $model = new RatingModel();

        // Récupération du commentaire par son ID
        $rating = $model->find($id);

        // Vérification si le commentaire existe
        if (!$rating) {
            return $this->failNotFound('Rating not found');
        }

        // Réponse avec les données du commentaire
        return $this->respond($rating);
    }

    // Méthode pour afficher la liste des commentaires
    public function index()
    {
        $model = new RatingModel();

        // Récupération de tous les commentaires
        $ratings = $model->findAll();

        // Réponse avec la liste des commentaires
        return $this->respond($ratings);
    }

    // Méthode pour mettre à jour un commentaire par ID
    public function update($id)
    {
        $model = new RatingModel();

        // Récupération des données depuis la requête HTTP PUT ou PATCH
        $data = $this->request->getRawInput();

        // Mise à jour du commentaire dans la base de données
        $model->update($id, $data);

        // Réponse de succès
        return $this->respond(['message' => 'Rating updated successfully']);
    }

    // Méthode pour supprimer un commentaire par ID
    public function delete($id)
    {
        $model = new RatingModel();

        // Vérification si le commentaire existe
        if (!$model->find($id)) {
            return $this->failNotFound('Rating not found');
        }

        // Suppression du commentaire de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Rating deleted successfully']);
    }
}
