<?php 

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'reservations';

    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer une RESA, mais garder la RESA présente dans l'historique LOGEMENT & USER 
    protected $useSoftDeletes = true;     
    
    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['start_date', 'end_date', 'chef_cuisine', 'visite', 'logement_id', 'user_id'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';

    public function getRating(int $reservationId) : array
    {
        return $this->db->table('ratings')
            ->select('id, rated, text, logement_id, reservation_id, user_id')
            ->where('reservation_id', $reservationId)
            ->get()
            ->getResultArray();
    }
    
    public function getUser($userId)
    {
        return $this->db->table('users')
                        ->select('id, name, email, tel')
                        ->where('id', $userId)
                        ->get()
                        ->getRowArray();
    }
    
}
