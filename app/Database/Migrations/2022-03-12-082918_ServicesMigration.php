<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ServicesMigration extends Migration
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
            'category_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
            ],
            'name' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'short_descr' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'long_descr' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>150,
            ],
            'price' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>10,
            ],
            'isactive' =>[
                "type" =>"ENUM",
                "constraint" =>['1','0'],
                "default" =>'1'
            ],
            'updated_at' =>[
                'type' =>'datetime',
                'null' =>true,
           ],
           'created_at datetime default current_timestamp',

         ]
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('services');
    }

    public function down()
    {
        $this->forge->createTable('services');
    }
}
