<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class QuotationsMigration extends Migration
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
            'quote_no' =>[
                'type' =>'TEXT',
                 'null' =>false,
            ],
            'client_id' =>[
                'type' =>'INT',
                'constraint' =>5,
                'unsigned' =>true,
              ],
            'quote_date' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>100,
            ],
            'valid_till' =>[
                'type' =>'VARCHAR',
                'null' =>false,
                'constraint' =>100,
            ],
            'sub_total' =>[
                'type' =>'INT',
                'constraint' =>35,
                'null' =>false,
            ],
            'gst' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'discount' =>[
                'type' =>'INT',
                'constraint' =>15,
                'null' =>false,
            ],
            'total' =>[
                'type' =>'INT',
                'constraint' =>25,
                'null' =>false,
            ],
            'status' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'remarks' =>[
                'type' =>'TEXT',
                'null' =>true,
            ],
            'is_deleted' =>[
                'type' =>'ENUM',
                'constraint' =>["1","0"],
                'default' =>"0",

            ],
            'ref_invoice_id' =>[
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
        $this->forge->createTable('quotations');
    }

    public function down()
    {
        $this->forge->dropTable('quotations');
    }
}
