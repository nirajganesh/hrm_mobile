<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProposalMigration extends Migration
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
            'client_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
            ],
            'file_src' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'short_descr' =>[
                'type' =>'VARCHAR',
                'null' =>true,
                'constraint' =>100,
            ],
            'descr' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>100,
            ],
            'status' =>[
                'type' =>'ENUM',
                'constraint' =>['1','0'],
                'default' =>'1',
            ],
            'follow_up_date' =>[
                "type" =>'VARCHAR',
                "constraint" =>100,
                "null" =>false
            ],
            'updated_at' =>[
                'type' =>'datetime',
                'null' =>true,
           ],
           'created_at datetime default current_timestamp',

        ],
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('proposal');
    }

    public function down()
    {
        $this->forge->dropTable('proposal');
    }
}
