<?php namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'ratings';
    
    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer une RESA, mais garder la RESA présente dans l'historique LOGEMENT & USER 
    protected $useSoftDeletes = true;     

    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['rated', 'text', 'logement_id', 'reservation_id', 'user_id'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';

    public function getUser($userId)
    {
        return $this->db->table('users')
                        ->select('id, name, email, tel')
                        ->where('id', $userId)
                        ->get()
                        ->getRowArray();
    }

    public function getResa($resaId)
    {
        return $this->db->table('reservations')
                        ->select('id, start_date, end_date')
                        ->where('id', $resaId)
                        ->get()
                        ->getRowArray();
    }

    public function getLogement($logementId)
    {
        return $this->db->table('logements')
                        ->select('id, name')
                        ->where('id', $logementId)
                        ->get()
                        ->getRowArray();
    }

}
