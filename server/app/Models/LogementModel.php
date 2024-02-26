<?php 

namespace App\Models;

use CodeIgniter\Model;

class LogementModel extends Model
{
    protected $table      = 'logements';
    protected $primaryKey = 'id';

    protected $allowedFields = ['images', 'secteur', 'description', 'tarif_bas', 'tarif_moyen', 'tarif_haut', 'm_carre', 'chambre', 'salle_de_bain', 'categorie', 'type', 'equipements', 'rating'];

    public function ratings()
    {
        return $this->hasMany('App\Models\RatingModel', 'logement', 'id');
    }
}
