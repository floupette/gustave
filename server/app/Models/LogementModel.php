<?php

namespace App\Models;

use CodeIgniter\Model;

class LogementModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'logements';
    
    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer un LOGEMENT, mais garder le LOGEMENT présent dans l'historique USER & RESA
    protected $useSoftDeletes = true; 

    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['name', 'images', 'secteur', 'description', 'tarif_bas', 'tarif_moyen', 'tarif_haut', 'm_carre', 'chambre', 'salle_de_bain', 'categorie', 'type'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';

    public function getEquipements(int $logementId) : array
    {
        // Exemple de jointure pour récupérer les équipements associés à un logement spécifique
        return $this->db->table('logement_equipement')
            ->join('equipements', 'equipements.id = logement_equipement.equipement_id')
            ->where('logement_equipement.logement_id', $logementId)
            ->get()
            ->getResultArray();
    }

    public function getReservation(int $logementId) : array
    {
        return $this->db->table('reservations')
            ->select('id, start_date, end_date')
            ->where('logement_id', $logementId)
            ->get()
            ->getResultArray();
    }

    public function getRating(int $logementId) : array
    {
        return $this->db->table('ratings')
            ->select('id, rated, text, logement_id, reservation_id, user_id')
            ->where('logement_id', $logementId)
            ->get()
            ->getResultArray();
    }

    public function uploadImages($imageFiles) {
        $imageNames = [];
        
        foreach ($imageFiles as $imageFile) {
            // Génération d'un nom de fichier unique
            $imageName = sha1(uniqid(rand(), true)) . '.' . $imageFile->getExtension();
        
            // Déplacement du fichier vers le répertoire de stockage des images
            $imageFile->move(WRITEPATH . 'uploads', $imageName);
        
            // Ajout du nom de l'image au tableau
            $imageNames[] = $imageName;
        }
        
        // Retourner le tableau des noms des images pour stockage dans la base de données
        return $imageNames;
    }  
    
}
