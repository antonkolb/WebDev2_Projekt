<?php

use yii\db\Migration;

class m160702_201023_init_tt_table extends Migration
{
    public function up()
    {
      $this->createTable(
      'tt',['id'=>'pk', 'name'=>'string uniqe', 'hurly'=>'integer',]
       );
    }

    public function down()
    {
        $this->dropTable('tt');
        echo "m160702_201023_init_tt_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}