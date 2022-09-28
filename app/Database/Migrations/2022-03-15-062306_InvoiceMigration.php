<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InvoiceMigration extends Migration
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
            'invoice_no' =>[
              'type' =>'TEXT',
              'null'=>false,
            ],
            'client_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
              ],
              'inv_date' =>[
                'type' =>'TEXT',
                'null' =>false,
             ],
            'sub_total' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>100,
            ],
            'payment_date' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'gst' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'total' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>100,
            ],
            'total_paid' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>100,
            ],
            'total_due' =>[
                'type' =>'INT',
                'null' =>false,
                'constraint' =>100,
            ],
            'due_date' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'remarks' =>[
                'type' =>'TEXT',
                'null' =>false,
            ],
            'is_deleted' =>[
                'type' =>'ENUM',
                'constraint' =>["1","0"],
                'default' =>"1",
            ],
            'ref_quotation_id' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
           'updated_at datetime default current_timestamp',
           'created_at datetime default current_timestamp',
        ],
        );
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('invoice');
    }

    public function down()
    {
        $this->forge->dropTable('invoice');
    }
}
