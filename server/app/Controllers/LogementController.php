<?php

namespace App\Controllers;

use App\Models\LogementModel;
use App\Models\LogementEquipementModel;
use App\Models\EquipementModel;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;

class LogementController extends Controller
{
    use ResponseTrait;

    public function create()
    {
        // Récupérer les données du formulaire
        $data = $this->request->getJSON();
    
        // Récupérer les données du formulaire
        $name = $data->name;
        $images = $data->images;
        $secteur = $data->secteur;
        $description = $data->description;
        $tarif_bas = $data->tarif_bas;
        $tarif_moyen = $data->tarif_moyen;
        $tarif_haut = $data->tarif_haut;
        $m_carre = $data->m_carre;
        $chambre = $data->chambre;
        $salle_de_bain = $data->salle_de_bain;
        $categorie = $data->categorie;
        $type = $data->type;
    
        // Équipements associés au logement
        $equipements = $data->equipements;
    
        $model = new LogementModel();
        $existingLogement = $model->where('name', $name)->first();
    
        if ($existingLogement) {
            return $this->fail('Le logement est déjà enregistré.', 400);
        }
    
        // Créer un nouveau logement
        $logementData = [
            'name' => $name,
            'images' => $images,
            'secteur' => $secteur,
            'description' => $description,
            'tarif_bas' => $tarif_bas,
            'tarif_moyen' => $tarif_moyen,
            'tarif_haut' => $tarif_haut,
            'm_carre' => $m_carre,
            'chambre' => $chambre,
            'salle_de_bain' => $salle_de_bain,
            'categorie' => $categorie,
            'type' => $type
        ];
    
        // Insertion des données du logement dans la base de données
        $model->insert($logementData);
    
        // Récupérer l'ID du logement nouvellement créé
        $logementId = $model->insertID();
    
        // Enregistrer les équipements associés au logement
        $logementEquipementModel = new LogementEquipementModel();
        foreach ($equipements as $equipement) {
            // Vérifier si l'équipement existe dans la table equipements
            $equipementModel = new EquipementModel();
            $existingEquipement = $equipementModel->find($equipement->id);
            
            if (!$existingEquipement) {
                // Si l'équipement n'existe pas, vous pouvez ignorer ou gérer cette situation selon vos besoins.
                // Dans cet exemple, nous affichons simplement un message d'erreur et passons à l'itération suivante.
                echo "L'équipement avec l'ID " . $equipement->id . " n'existe pas.";
                continue;
            }
            
            // Si l'équipement existe, vous pouvez procéder à l'insertion dans la table logement_equipement
            $logementEquipementData = [
                'logement_id' => $logementId,
                'equipement_id' => $equipement->id
            ];
            $logementEquipementModel->insert($logementEquipementData);
        }
    
        // Réponse de succès
        return $this->respondCreated(['message' => 'Logement créé avec succès']);
    }   


    // Méthode pour afficher un logement par ID avec ses équipements, réservations et évaluations
    public function show($id)
    {
        $model = new LogementModel();

        // Récupération du logement par ID
        $logement = $model->find($id);

        if (!$logement) {
            return $this->fail('Logement non trouvé.', 404);
        }

        // Récupération des équipements associés au logement
        $equipements = $model->getEquipements($id);

        // Récupération des réservations associées au logement
        $reservations = $model->getReservation($id);

        // Récupération des évaluations (ratings) associées au logement
        $ratings = $model->getRating($id);

        // Ajout des équipements, réservations et évaluations au logement
        $logement['equipements'] = $equipements;
        $logement['reservations'] = $reservations;
        $logement['ratings'] = $ratings;

        // Réponse avec les détails du logement
        return $this->respond($logement);
    }

    // Méthode pour afficher la liste de tous les logements avec leurs équipements, réservations et évaluations
    public function index()
    {
        $model = new LogementModel();

        // Récupération de tous les logements
        $logements = $model->findAll();

        // Pour chaque logement, ajouter les équipements, réservations et évaluations
        foreach ($logements as &$logement) {
            $logementId = $logement['id'];
            $logement['equipements'] = $model->getEquipements($logementId);
            $logement['reservations'] = $model->getReservation($logementId);
            $logement['ratings'] = $model->getRating($logementId);
        }

        // Réponse avec la liste des logements
        return $this->respond($logements);
    }


    // Méthode pour mettre à jour un logement par ID avec ses équipements
    public function update($id)
    {
        // Récupérer les données du formulaire JSON
        $data = $this->request->getJSON(true);
    
        // Vérifier si le logement existe
        $model = new LogementModel();
        $existingLogement = $model->find($id);
        if (!$existingLogement) {
            return $this->fail('Logement non trouvé.', 404);
        }
    
        // Supprimer les équipements associés au logement
        $logementEquipementModel = new LogementEquipementModel();
        $logementEquipementModel->where('logement_id', $id)->delete();
    
        // Mise à jour du logement
        $logementData = [
            'name' => $data['name'],
            'images' => $data['images'],
            'secteur' => $data['secteur'],
            'description' => $data['description'],
            'tarif_bas' => $data['tarif_bas'],
            'tarif_moyen' => $data['tarif_moyen'],
            'tarif_haut' => $data['tarif_haut'],
            'm_carre' => $data['m_carre'],
            'chambre' => $data['chambre'],
            'salle_de_bain' => $data['salle_de_bain'],
            'categorie' => $data['categorie'],
            'type' => $data['type']
        ];
        $model->update($id, $logementData);
    
        // Réinsérer les équipements associés au logement
        $equipements = $data['equipements'];
        foreach ($equipements as $equipement) {
            $logementEquipementData = [
                'logement_id' => $id,
                'equipement_id' => $equipement['id']
            ];
            $logementEquipementModel->insert($logementEquipementData);
        }
    
        // Réponse de succès
        return $this->respond(['message' => 'Logement modifié avec succès']);
    }
    


    // Méthode pour supprimer un logement par ID
    public function delete($id)
    {
        $model = new LogementModel();

        // Vérification si le logement existe
        if (!$model->find($id)) {
            return $this->failNotFound('Logement non trouvé');
        }

        // Suppression du logement de la base de données
        $model->delete($id);

        // Réponse de succès
        return $this->respondDeleted(['message' => 'Logement supprimé avec succès']);
    }
}