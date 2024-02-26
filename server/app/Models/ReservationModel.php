<?php 

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    protected $table      = 'reservations';
    protected $primaryKey = 'id';

    protected $allowedFields = ['start_date', 'end_date', 'chef_cuisine', 'visite', 'logement', 'user'];

    // Définir les relations si nécessaire
}
