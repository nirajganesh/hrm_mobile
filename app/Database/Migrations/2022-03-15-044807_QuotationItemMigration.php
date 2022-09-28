<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class QuotationItemMigration extends Migration
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
            'quotation_id' =>[
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
                'constraint' =>100,
            ],
            'price' =>[
                'type' =>'INT',
                'constraint' =>35,
                'null' =>false,
            ],
            'qty' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>100,
            ],
            'updated_at' =>[
                'type' =>'datetime',
                'null' =>true,
           ],
           'created_at datetime default current_timestamp',

        ],
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('quotation_item');
    }

    public function down()
    {
        $this->forge->dropTable('quotation_item');
    }
}
