<?php

namespace App\Models;

use CodeIgniter\Model;

class LogementEquipementModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'logement_equipement';

    // Nom de la clé primaire de la table
    protected $primaryKey = 'id';

    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['logement_id', 'equipement_id'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = false;

}
