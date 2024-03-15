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

    // Méthode pour créer un logement
    public function create()
    {

        // Récupérer les données du formulaire
        $data = $this->request->getPost();

        //var_dump($data);

        // Récupérer les fichiers téléchargés
        $uploadedFiles = $this->request->getFiles();

        // dd($uploadedFiles);

        // Récupérer les données du formulaire
        $name = $data['name'];
        $secteur = $data['secteur'];
        $description = $data['description'];
        $tarif_bas = $data['tarif_bas'];
        $tarif_moyen = $data['tarif_moyen'];
        $tarif_haut = $data['tarif_haut'];
        $m_carre = $data['m_carre'];
        $chambre = $data['chambre'];
        $salle_de_bain = $data['salle_de_bain'];
        $categorie = $data['categorie'];
        $type = $data['type'];

        // Équipements associés au logement
        $equipements = json_decode($data['equipements']);

        // Créer un tableau pour stocker les noms des fichiers téléchargés
        $imageNames = [];

        // Instancier le modèle LogementModel
        $model = new LogementModel();

        // Vérifie si le nom du logement existe déjà
        $existingLogement = $model->where('name', $name)->first();
    
        if ($existingLogement) {
            return $this->fail('Le logement est déjà enregistré.', 400);
        }
                
        // Vérification si des fichiers images sont envoyés
        if (!empty($uploadedFiles)) {
            foreach ($uploadedFiles['images'] as $file) {
                // Vérification de la validité du fichier image
                if ($file->isValid() && !$file->hasMoved()) {
                    // Upload de l'image
                    // Génération d'un seul nom de fichier unique pour chaque image
                    $uniqueFileName = sha1(uniqid(rand(), true));
                    // Obtenez l'extension du fichier
                    $extension = $file->getExtension();
                    // Générez le nom de fichier complet
                    $imageName = $uniqueFileName . '.' . $extension ;
                    // Déplacement du fichier vers le répertoire de stockage des images
                    $file->move(WRITEPATH . 'uploads', $imageName);
                    // Renommage de l'image en format JSON après avoir été enregistré dans le bon chemin
                    $imageName = '"' . $uniqueFileName . '.' . $extension . '"' ;
                    if ($imageName !== false) {
                        // Ajout du nom de l'image au tableau
                        array_push($imageNames, $imageName);
                    } else {
                        // Gestion des erreurs d'upload d'image
                        return $this->fail('Erreur lors de l\'upload de l\'image', 500);
                    }
                }
            }
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
            'type' => $type,
            'images' => '[' . implode(',', $imageNames) . ']',
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
    
        // Vérifier si $logement['images'] est null
        if (!is_null($logement['images'])) {

            //var_dump($logement['images']);

            // Convertir les images en tableau s'il s'agit d'une chaîne JSON
            $images = json_decode($logement['images'], true);

            // Vérifier si la conversion en tableau a réussi
            if ($images !== null) {
                // Ajouter le chemin du dossier où sont stockées les images
                $basePath = base_url() . 'uploads/';

                // Ajouter le chemin complet de chaque image
                $logement['images'] = array_map(function ($imageName) use ($basePath) {
                    return $basePath . $imageName;
                }, $images);
            } else {
                // Si la conversion en tableau a échoué, attribuer un tableau contenant l'URL actuelle à $logement['images']
                $logement['images'] = [base_url() . $logement['images']];
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

                //var_dump($logement['images']);

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
        // Récupérer les données du formulaire
        $data = $this->request->getPost();

        // Récupérer le logement à mettre à jour depuis la base de données
        $model = new LogementModel();
        $logement = $model->find($id);
        
        // Vérifier si le logement existe
        if (!$logement) {
            return $this->fail('Logement non trouvé', 404);
        }

        //var_dump($data);
        
        // Récupérer les données du formulaire
        $name = $data['name'];
        $secteur = $data['secteur'];
        $description = $data['description'];
        $tarif_bas = $data['tarif_bas'];
        $tarif_moyen = $data['tarif_moyen'];
        $tarif_haut = $data['tarif_haut'];
        $m_carre = $data['m_carre'];
        $chambre = $data['chambre'];
        $salle_de_bain = $data['salle_de_bain'];
        $categorie = $data['categorie'];
        $type = $data['type'];

        // Créer un tableau pour stocker les noms des fichiers téléchargés
        $imageNames = [];

        // Récupérer les fichiers téléchargés
        $uploadedFiles = $this->request->getFiles();
  
        // Récupérer les images existantes
        $existingImages = json_decode($logement['images'], true) ?? [];

        // Vérification si des fichiers images sont envoyés
        if (!empty($uploadedFiles['images'] ?? [])) {
            foreach ($uploadedFiles['images'] as $file) {
                // Vérification de la validité du fichier image
                if ($file->isValid() && !$file->hasMoved()) {
                    // Upload de l'image
                    // Génération d'un seul nom de fichier unique pour chaque image
                    $uniqueFileName = sha1(uniqid(rand(), true));
                    // Obtenez l'extension du fichier
                    $extension = $file->getExtension();
                    // Générez le nom de fichier complet
                    $imageName = $uniqueFileName . '.' . $extension;
                    // Déplacement du fichier vers le répertoire de stockage des images
                    $file->move(WRITEPATH . 'uploads', $imageName);
                    // Ajout du nom de l'image au tableau
                    $imageNames[] = $imageName;
                }
            }
        }

        // Supprimer les images du dossier uploads si elles ne sont pas trouvées dans les nouvelles images
        foreach ($existingImages as $existingImage) {
            if (!in_array($existingImage, $imageNames)) {
                unlink(WRITEPATH . 'uploads/' . $existingImage);
            }
        }

        // Mettre à jour les images du logement dans la base de données
        $model->update($id, ['images' => json_encode($imageNames)]);
        
        // Mettre à jour les autres champs du logement
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
            'type' => $type,
        ];
        
        // Mettre à jour le logement dans la base de données
        $model->update($id, $logementData);
        
        // Mettre à jour les équipements associés au logement
        $logementEquipementModel = new LogementEquipementModel();
        $logementEquipementModel->where('logement_id', $id)->delete();

        // Équipements associés au logement
        $equipements = isset($data['equipements']) ? json_decode($data['equipements']) : ($logement['equipements']);
        foreach ($equipements as $equipement) {
            $logementEquipementData = [
                'logement_id' => $id,
                'equipement_id' => $equipement->id
            ];
            $logementEquipementModel->insert($logementEquipementData);
        }
        
        // Réponse de succès
        return $this->respondUpdated(['message' => 'Logement mis à jour avec succès']);
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