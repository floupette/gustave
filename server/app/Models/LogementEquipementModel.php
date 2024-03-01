<?php

namespace App\Models;

use CodeIgniter\Model;

class LogementEquipementModel extends Model
{
    protected $table = 'logement_equipement';
    protected $primaryKey = 'id';
    protected $allowedFields = ['logement_id', 'equipement_id'];

    // Définir les relations avec les autres modèles si nécessaire
    protected $returnType = 'object';

    // Méthode pour récupérer les équipements associés à un logement
    public function getEquipementsByLogement($logementId)
    {
        return $this->select('equipement_id')
                    ->where('logement_id', $logementId)
                    ->findAll();
    }

    // Méthode pour associer des équipements à un logement
    public function associateEquipements($logementId, $equipements)
    {
        // Supprimer d'abord toutes les associations existantes pour ce logement
        $this->where('logement_id', $logementId)->delete();

        // Insérer les nouvelles associations
        foreach ($equipements as $equipementId) {
            $data = [
                'logement_id' => $logementId,
                'equipement_id' => $equipementId
            ];
            $this->insert($data);
        }
    }
}
