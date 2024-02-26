<?php 

namespace App\Models;

use CodeIgniter\Model;

class EquipementModel extends Model
{
    protected $table      = 'equipements';
    protected $primaryKey = 'id';

    protected $allowedFields = ['name'];
}
