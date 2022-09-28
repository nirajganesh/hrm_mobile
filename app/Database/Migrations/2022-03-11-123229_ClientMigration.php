<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientMigration extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' =>[
              'type' =>'INT',
              'constraint' =>5,
              'unsigned' =>true,
              'auto_increment' =>true,
            ],
            'name' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'person' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'address' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'contact_no' =>[
                'type' =>'INT',
                'constraints' =>10,
                'null' =>false,
            ],
            'email' =>[
                'type' =>'VARCHAR',
                'constraint' =>100,
                'null' =>false,
            ],
            'gst_no' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'remarks' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'balance' =>[
                'type' =>'INT',
                'constraint' =>10,
            ],
            'updated_at' =>[
                'type' =>'datetime',
                'null' =>true,
           ],
           'created_at datetime default current_timestamp',

         ]
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
