<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run()
    {
        $ratings = [
            [   
                'rated' => 4,
                'text' => 'Parfait ! sauf la machine a café',
                'logement_id' => 1,
                'reservation_id' => 1,
                'user_id' => 2,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'rated' => 5,
                'text' => 'Thanks',
                'logement_id' => 2,
                'reservation_id' => 2,
                'user_id' => 3,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'rated' => 3,
                'text' => 'La piscine laisse a désirer...',
                'logement_id' => 3,
                'reservation_id' => 3,
                'user_id' => 1,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
         
          
        ];

        // Insertion des données dans la table clients
        $this->db->table('ratings')->insertBatch($ratings);
    }
}
