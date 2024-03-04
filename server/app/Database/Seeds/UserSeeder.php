<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [   
                'email' => 'jean.dupont@example.com',
                'name' => 'Jean Dupont',
                'tel' => '0123456789',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_admin' => false,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'email' => 'marie.martin@example.com',
                'name' => 'Marie Martin',
                'tel' => '0234567891',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_admin' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'email' => 'pierre.durand@example.com',
                'name' => 'Pierre Durand',
                'tel' => '0345678912',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_admin' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'email' => 'lemaildesophielambert@gmail.com',
                'name' => 'Sophie Lambert',
                'tel' => '0456789123',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_admin' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'email' => 'alexandre.buet@institutsolacroup.com',
                'name' => 'Alexandre Buet',
                'tel' => '0456789122',
                'password' => password_hash('dptest', PASSWORD_DEFAULT),
                'is_admin' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'email' => 'antoine.pestel.ropars@institutsolacroup.com',
                'name' => 'Antoine Pestel',
                'tel' => '0456789127',
                'password' => password_hash('toortoor', PASSWORD_DEFAULT),
                'is_admin' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'email' => 'antoine.dubois@example.com',
                'name' => 'Antoine Dubois',
                'tel' => '0567891234',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'is_admin' => false,
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s'), 
            ]
        ];

        // Insertion des données dans la table clients
        $this->db->table('users')->insertBatch($users);
    }
}
