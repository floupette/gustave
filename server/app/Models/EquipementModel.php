<?php 

namespace App\Models;

use CodeIgniter\Model;

class EquipementModel extends Model
{
    // Nom de la table dans la base de données
    protected $table      = 'equipements';
    
    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer un EQUIPEMENT, mais garder l'EQUIPEMENT présent dans les précedents LOGEMENTS
    protected $useSoftDeletes = true; 

    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['name'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';

}
