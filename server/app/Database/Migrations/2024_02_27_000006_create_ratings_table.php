<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRatingsTable extends Migration
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
            'rated' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'logement_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'reservation_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
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
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('logement_id', 'logements', 'id');
        $this->forge->addForeignKey('reservation_id', 'reservations', 'id');
        $this->forge->addForeignKey('user_id', 'users', 'id');
        $this->forge->createTable('ratings');
    }

    public function down()
    {
        $this->forge->dropTable('ratings');
    }
}
