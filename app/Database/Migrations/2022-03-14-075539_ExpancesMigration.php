<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ExpancesMigration extends Migration
{
    public function up()
    {
        $this->forge->addField(
            [
            'id' =>[
              'type' =>'INT',
              'constraint' =>5,
              'unsigned' =>true,
              'auto_increment' =>true,
            ],
            'user_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
            ],
            'descr' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>100,
            ],
            'amount' =>[
                'type' =>'INT',
                'constraint' =>15,
                'null' =>false,
            ],
            'file_src' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'updated_at' =>[
                'type' =>'datetime',
                'null' =>true,
           ],
           'created_at datetime default current_timestamp',

        ],
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('expenses');
    }

    public function down()
    {
        $this->forge->dropTable('expenses');
    }
}
