<?php 

namespace App\Models;

use CodeIgniter\Model;

class LogementModel extends Model
{
    protected $table = 'logements';
    protected $primaryKey = 'id';

    protected $allowedFields = ['images', 'secteur', 'description', 'tarif_bas', 'tarif_moyen', 'tarif_haut', 'm_carre', 'chambre', 'salle_de_bain', 'categorie', 'type', 'equipements', 'rating'];
      
    // Relation avec les équipements (many-to-many)
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;
    protected $skipValidation = true;
    protected $beforeInsert = ['associations'];
    protected $beforeUpdate = ['associations'];

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $validation         = false;
    
    protected function associations(array $data)
    {
        // Récupérer les équipements associés au logement
        $equipements = $data['equipements'] ?? null;
        unset($data['equipements']); // Retirer les équipements du tableau de données

        // Insérer le logement dans la base de données
        $logementId = $this->insert($data);

        // Si des équipements ont été sélectionnés
        if (!empty($equipements)) {
            // Récupérer le modèle de la table de liaison logement_equipement
            $logementEquipementModel = new LogementEquipementModel();

            // Associer les équipements au logement
            $logementEquipementModel->associateEquipements($logementId, $equipements);
        }

        return $data;
    }
    
    public function equipements()
    {
        return $this->belongsToMany('Models\EquipementModel', 'logement_equipement', 'logement_id', 'equipement_id');
    }
    
    
    public function ratings()
    {
        return $this->hasMany('Models\RatingModel', 'logement', 'id');
    }
}
