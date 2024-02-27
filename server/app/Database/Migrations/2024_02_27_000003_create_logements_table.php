<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogementsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'images' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'secteur' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tarif_bas' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'tarif_moyen' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'tarif_haut' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'm_carre' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0,
            ],
            'chambre' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'salle_de_bain' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
            'categorie' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'type' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'rating' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('logements');
    }

    public function down()
    {
        $this->forge->dropTable('logements');
    }
}
