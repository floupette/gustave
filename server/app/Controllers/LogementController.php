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
    
        // Vérification si des fichiers images sont envoyés
        if ($images = $this->request->getFiles()) {
            $uploadedImages = [];

            foreach ($images as $image) {
                // Vérification de la validité du fichier image
                if ($image->isValid() && !$image->hasMoved()) {
                    // Upload de l'image
                    $uploadedImages[] = $model->uploadImages([$image]); // Passer le fichier image sous forme de tableau
                } else {
                    // Gestion des erreurs d'upload d'image
                    return $this->fail('Erreur lors de l\'upload de l\'image', 500);
                }
            }

            // Attribution des noms des images au logement
            $logementData['images'] = json_encode($uploadedImages);
        }

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
    
        // Vérifier si $logement['images'] est null
        if (!is_null($logement['images'])) {
            // Convertir les images en tableau s'il s'agit d'une chaîne JSON
            $logement['images'] = json_decode($logement['images'], true);

            // Vérifier si la conversion en tableau a réussi
            if (is_array($logement['images'])) {
                // Ajouter le chemin du dossier où sont stockées les images
                $basePath = base_url() . 'uploads/';

                // Ajouter le chemin complet de chaque image
                $logement['images'] = array_map(function ($imageName) use ($basePath) {
                    return $basePath . $imageName;
                }, $logement['images']);
            } else {
                // Si la conversion en tableau a échoué, attribuer un tableau vide à $logement['images']
                $logement['images'] = [];
            }
        } else {
            // Si $logement['images'] est null, attribuer un tableau vide à $logement['images']
            $logement['images'] = [];
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
    
        // Ajouter le chemin du dossier où sont stockées les images
        $basePath = base_url() . 'uploads/';
    
        // Pour chaque logement, ajouter les informations sur les images
        foreach ($logements as &$logement) {
            // Vérifier si $logement['images'] est null
            if (!is_null($logement['images'])) {
                // Convertir les images en tableau s'il s'agit d'une chaîne JSON
                $logement['images'] = json_decode($logement['images'], true);
                
                // Vérifier si la conversion en tableau a réussi
                if (is_array($logement['images'])) {
                    // Ajouter le chemin complet de chaque image
                    $logement['images'] = array_map(function ($imageName) use ($basePath) {
                        return $basePath . $imageName;
                    }, $logement['images']);
                } else {
                    // Si la conversion en tableau a échoué, attribuer un tableau vide à $logement['images']
                    $logement['images'] = [];
                }
            } else {
                // Si $logement['images'] est null, attribuer un tableau vide à $logement['images']
                $logement['images'] = [];
            }
    
            // Récupération des équipements associés au logement
            $equipements = $model->getEquipements($logement['id']);
    
            // Récupération des réservations associées au logement
            $reservations = $model->getReservation($logement['id']);
    
            // Récupération des évaluations (ratings) associées au logement
            $ratings = $model->getRating($logement['id']);
    
            // Ajout des équipements, réservations et évaluations au logement
            $logement['equipements'] = $equipements;
            $logement['reservations'] = $reservations;
            $logement['ratings'] = $ratings;
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

        // Vérification si des fichiers images sont envoyés
        if ($images = $this->request->getFiles()) {
            $uploadedImages = [];

            foreach ($images['images'] as $image) {
                // Vérification de la validité du fichier image
                if ($image->isValid() && !$image->hasMoved()) {
                    // Upload de l'image
                    $uploadedImages[] = $model->uploadImage($image);
                } else {
                    // Gestion des erreurs d'upload d'image
                    return $this->fail('Erreur lors de l\'upload de l\'image', 500);
                }
            }

            // Attribution des noms des images au logement
            $logementData['images'] = json_encode($uploadedImages);
        }

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