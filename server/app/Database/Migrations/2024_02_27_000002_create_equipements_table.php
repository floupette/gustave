<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEquipementsTable extends Migration
{
    public function up()
    {
        // Définition des colonnes de la table equipements
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ];

        // Ajout de la clé primaire
        $this->forge->addField('id');
        $this->forge->addKey('id', true);

        // Création de la table equipements
        $this->forge->addField($fields);
        $this->forge->createTable('equipements');
    }

    public function down()
    {
        // Suppression de la table equipements si elle existe
        $this->forge->dropTable('equipements');
    }
}
