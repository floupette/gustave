<?php namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table      = 'ratings';
    protected $primaryKey = 'id';

    protected $allowedFields = ['rated', 'text', 'logement', 'reservation', 'user'];

    // Définir les relations si nécessaire
}
