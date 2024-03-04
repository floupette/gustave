<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EquipementSeeder extends Seeder
{
    public function run()
    {
        $equipements = [
            [   
                'name' => 'Parc de jeux',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'name' => 'Jardin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'name' => 'Tennis',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'name' => 'Centre équestre',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'Piscine',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'Spa',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'Jacuzzi',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Golf',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Equipement de sport',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // Insertion des données dans la table clients
        $this->db->table('equipements')->insertBatch($equipements);
    }
}
