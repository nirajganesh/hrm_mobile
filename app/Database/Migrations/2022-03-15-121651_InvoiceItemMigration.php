<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InvoiceItemMigration extends Migration
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
            'invoice_id' =>[
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
        $this->forge->createTable('invoice_item');
    }

    public function down()
    {
        $this->forge->createTable('invoice_item');
    }
}
