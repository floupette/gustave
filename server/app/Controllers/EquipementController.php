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

        // Récupérer les données du formulaire
        $data = $this->request->getJSON();

        // Récupérer les données du formulaire
        $name = $data->name;

        $model = new EquipementModel();

        $existingName = $model->where('name', $name)->first();

        if ($existingName) {
            return $this->fail('L\'équipement existe déjà.', 400);
        } else {        
            // Créer un nouvel equipement
            $equipementData = [
                'name' => $name,
            ];
        }

        // Insertion des données dans la base de données
        $model->insert($equipementData);

        // Réponse de succès
        return $this->respondCreated(['message' => $equipementData['name'] . ' crée avec succès']);
    }

    // Méthode pour afficher un équipement par ID
    public function show($id)
    {
        $model = new EquipementModel();

        // Récupération de l'équipement par son ID
        $equipement = $model->find($id);

        // Vérification si l'équipement existe
        if (!$equipement) {
            return $this->failNotFound('Equipement non trouvé');
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

        // Récupération de l'equipement par son ID
        $equipement = $model->find($id);

        // Vérification si l'équipement existe
        if (!$equipement) {
            return $this->failNotFound('Equipement non trouvé');
        }

        // Récupération des données de la requête PUT
        $data = $this->request->getJSON(true); // Récupération des données sous forme de tableau associatif

        // Vérifier si l'equipement existe déjà
        $existingEquipement = $model->where('name', $data['name'])->where('id !=', $id)->first();

        if ($existingEquipement) {
            return $this->fail('L\'équipement est déjà présent.', 400);
        } 
        // Mettre à jour les champs
        $equipement['name'] = isset($data['name']) ? $data['name'] : $equipement['name'];

        // Mise à jour de l'équipement dans la base de données
        $model->update($id, $equipement);

        // Réponse de succès
        return $this->respond(['message' => $data['name'] . ' modifié avec succes']);
    }

    // Méthode pour supprimer un équipement par ID
    public function delete($id)
    {
        $model = new EquipementModel();

        
        // Récupération de l'utilisateur par son ID
        $equipement = $model->find($id);

        // Vérification si l'équipement existe
        if (!$equipement) {
            return $this->failNotFound('Equipement non trouvé');
        }

        // Suppression de l'équipement de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' =>  $equipement['name'] . '  supprimé avec succès']);
    }
}
