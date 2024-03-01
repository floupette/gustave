<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'users';

    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer un USER, mais garder le USER présent dans l'historique RESAS ou RATING
    protected $useSoftDeletes = true; 

    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['email', 'name', 'tel', 'password', 'is_admin'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';
  
    public function getReservation(int $userId) : array
    {
        return $this->db->table('reservations')
            ->select('id, start_date, end_date')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
    }

    public function getRating(int $userId) : array
    {
        return $this->db->table('ratings')
            ->select('id, rated, text, logement_id, reservation_id, user_id')
            ->where('user_id', $userId)
            ->get()
            ->getResultArray();
    }
}
