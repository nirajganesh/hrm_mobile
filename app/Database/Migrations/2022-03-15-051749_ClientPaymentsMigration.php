<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ClientPaymentsMigration extends Migration
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
            'receipt_no' =>[
              'type' =>'TEXT',
              'null'=>false,
            ],
            'client_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
              ],
            'amount' =>[
                'type' =>'INT',
                'constraint' =>35,
                'null' =>false,
            ],
            'invoice_id' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>100,
            ],
            'payment_date' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'remarks' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
           'updated_at datetime default current_timestamp',
           'created_at datetime default current_timestamp',
        ],
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('client_payments');
    }

    public function down()
    {
        $this->forge->dropTable('client_payments');
    }
}
