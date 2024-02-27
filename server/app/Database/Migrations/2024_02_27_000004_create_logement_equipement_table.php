<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogementEquipementTable extends Migration
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
            'logement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'equipement_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('logement_id', 'logements', 'id');
        $this->forge->addForeignKey('equipement_id', 'equipements', 'id');
        $this->forge->createTable('logement_equipement');
    }

    public function down()
    {
        $this->forge->dropTable('logement_equipement');
    }
}