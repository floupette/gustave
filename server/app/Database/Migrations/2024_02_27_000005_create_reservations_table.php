<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReservationsTable extends Migration
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
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
            ],
            'chef_cuisine' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'visite' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'logement' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('reservations');
    }

    public function down()
    {
        $this->forge->dropTable('reservations');
    }
}
