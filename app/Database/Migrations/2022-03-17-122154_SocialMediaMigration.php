<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SocialMediaMigration extends Migration
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
            'emp_id' =>[
                'type' =>'INT',
              'constraint' =>5,
              'unsigned' =>true,
            ],
            'facebook' =>[
                'type' =>'TEXT',
                'null' =>true, 
              ],
            'linkedin' =>[
                'type' =>'TEXT',
                'null' =>true, 
              ],
            'instagram' =>[
                'type' =>'TEXT',
                'null' =>true, 
              ],
            'skype_id' =>[
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
        $this->forge->createTable('social_media');
    }

    public function down()
    {
        $this->forge->dropTable('social_media');
    }
}
