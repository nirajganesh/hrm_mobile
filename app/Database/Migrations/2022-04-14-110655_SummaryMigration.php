<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SummaryMigration extends Migration
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
            'client_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
              ],
            'item_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
              ],
            'descr' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'qty' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'is_billed' =>[
                "type" =>"ENUM",
                "constraint" =>['1','0'],
                "default" =>'0'
            ],
            'date' =>[
                'type' =>'text',
                'constraint' =>30,
            ],
           'updated_at datetime default current_timestamp',
           'created_at datetime default current_timestamp',

         ]
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('summary');
    }

    public function down()
    {
        $this->forge->dropTable('summary');
    }
}
