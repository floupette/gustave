<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Logement_EquipementSeeder extends Seeder
{
    public function run()
    {
        $logement_equipement = [
            [   
                'logement_id' => 1,
                'equipement_id' => 1,  
            ],
            [
                'logement_id' => 1,
                'equipement_id' => 2,   
            ],
            [
                'logement_id' => 1,
                'equipement_id' => 3, 
            ],
            [
                'logement_id' => 3 ,
                'equipement_id' => 4,   
            ],
            [
                'logement_id' => 3,
                'equipement_id' => 5,    
            ],
            [
                'logement_id' => 3,
                'equipement_id' => 6,    
            ],
            [
                'logement_id' => 4,
                'equipement_id' => 7, 
            ],
            [
                'logement_id' => 4,
                'equipement_id' => 8, 
            ],
            [
                'logement_id' => 5,
                'equipement_id' => 9, 
            ],
            [
                'logement_id' => 5,
                'equipement_id' => 1,    
            ],
            [
                'logement_id' => 6,
                'equipement_id' => 2, 
            ],
            [
                'logement_id' => 6,
                'equipement_id' => 3, 
            ],
            [
                'logement_id' => 7,
                'equipement_id' => 4, 
            ],
            [
                'logement_id' => 7,
                'equipement_id' => 5, 
            ],
            
        ];

        // Insertion des données dans la table Logement_equipement
        $this->db->table('logement_equipement')->insertBatch($logement_equipement);
    }
}
