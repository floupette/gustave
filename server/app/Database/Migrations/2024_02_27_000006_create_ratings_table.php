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
            'logement' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'reservation' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('ratings');
    }

    public function down()
    {
        $this->forge->dropTable('ratings');
    }
}
