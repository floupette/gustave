<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LogementSeeder extends Seeder
{
    public function run()
    {
        $logements = [
            [   
                'name' => 'Belle époque',
                'images' => "[\"Belle_epoque.jpg\",\"Belle_epoque.jpg\"]",
                'secteur' => 'Fontainbleau',
                'description' => 'Le faste des Rois. Joséphine de Beauharnais y a résidé',
                'tarif_bas' => 3000,
                'tarif_moyen' => 5000, 
                'tarif_haut' => 8000, 
                'm_carre' => 270,
                'chambre'=> 10,
                'salle_de_bain'=> 6,
                'categorie'=> 'Ville',
                'type'=> 'Manoir',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),  
            ],
            [
                'name' => 'Buron des Cimes',
                'images' => "[\"Buron_des_Cimes.jpg\"]",
                'secteur' => 'Serre-Chevalier',
                'description' => 'Le luxe au coeur des montagnes! ',
                'tarif_bas' => 8000,
                'tarif_moyen' => 11000, 
                'tarif_haut' => 15000, 
                'm_carre' => 600,
                'chambre'=> 20,
                'salle_de_bain'=> 20,
                'categorie'=> 'Montagne',
                'type'=> 'Villa',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),   
            ],
            [
                'name' => 'Guarrigue',
                'images' => "[\"Garrigue.jpg\"]",
                'secteur' => 'Saint Maximin',
                'description' => 'Monter sur la Sainte Victoire et voir la mer, une fois dans sa vie! Savourer le beurre de truffe lors d’apéritifs trainants!',
                'tarif_bas' => 3000,
                'tarif_moyen' => 5000, 
                'tarif_haut' => 8000, 
                'm_carre' => 300,
                'chambre'=> 10,
                'salle_de_bain'=> 5,
                'categorie'=> 'Campagne',
                'type'=> 'Villa',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'), 
            ],
            [
                'name' => 'L\'alpage',
                'images' => "[\"L_alapage.jpg\"]",
                'secteur' => 'Voiron',
                'description' => 'La douceur des premières pentes, le repos de l’âme!',
                'tarif_bas' => 2000,
                'tarif_moyen' => 4000, 
                'tarif_haut' => 5000, 
                'm_carre' => 200,
                'chambre'=> 7,
                'salle_de_bain'=> 4,
                'categorie'=> 'Montagne',
                'type'=> 'Maison',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'La Cabane',
                'images' => "[\"La_Cabane.jpg\"]",
                'secteur' => 'Cahors',
                'description' => 'Un nid parfait pour renouveler votre amour!',
                'tarif_bas' => 1500,
                'tarif_moyen' => 2500, 
                'tarif_haut' => 3500, 
                'm_carre' => 110,
                'chambre'=> 4,
                'salle_de_bain'=> 2,
                'categorie'=> 'Campagne',
                'type'=> 'Atypique',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'La Galibe',
                'images' => "[\"La_Galibe.jpg\"]",
                'secteur' => 'Malestroit',
                'description' => 'Terre de Chouans!',
                'tarif_bas' => 3000,
                'tarif_moyen' => 5000, 
                'tarif_haut' => 8000, 
                'm_carre' => 300,
                'chambre'=> 10,
                'salle_de_bain'=> 5,
                'categorie'=> 'Campagne',
                'type'=> 'Maison',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),    
            ],
            [
                'name' => 'La maison dans les bois',
                'images' => "[\"La_maison_dans_les_bois.jpg\"]",
                'secteur' => 'Luz Saint Sauveur',
                'description' => 'Vous ne vous arrêterez pas ici, vous vous équiperez et partirez à l’assaut des cimes!',
                'tarif_bas' => 1500,
                'tarif_moyen' => 2500, 
                'tarif_haut' => 3500, 
                'm_carre' => 110,
                'chambre'=> 4,
                'salle_de_bain'=> 2,
                'categorie'=> 'Ville',
                'type'=> 'Atypique',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ];

        // Insertion des données dans la table clients
        $this->db->table('logements')->insertBatch($logements);
    }
}
