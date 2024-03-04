<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        $reservations = [
            [   
                'start_date' => '2024-05-04 15:00:00',
                'end_date' => '2024-05-09 15:00:00',
                'chef_cuisine' => true,
                'visite' => false,
                'logement_id' => 1,
                'user_id' => 2,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'start_date' => '2024-06-04 15:00:00',
                'end_date' => '2024-06-09 15:00:00',
                'chef_cuisine' => true,
                'visite' => false,
                'logement_id' => 2,
                'user_id' => 3,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'start_date' => '2024-04-10 15:00:00',
                'end_date' => '2024-04-20 15:00:00',
                'chef_cuisine' => true,
                'visite' => false,
                'logement_id' => 3,
                'user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'start_date' => '2024-04-02 15:00:00',
                'end_date' => '2024-04-20 15:00:00',
                'chef_cuisine' => true,
                'visite' => false,
                'logement_id' => 6,
                'user_id' => 5,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'start_date' => '2024-06-04 15:00:00',
                'end_date' => '2024-06-09 15:00:00',
                'chef_cuisine' => true,
                'visite' => false,
                'logement_id' => 7,
                'user_id' => 4,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            
        ];

        // Insertion des données dans la table clients
        $this->db->table('reservations')->insertBatch($reservations);
    }
}
