<?php

use yii\db\Migration;

class m170313_142921_createModulesTable2 extends Migration
{
    public function up()
    {
		$tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%modules}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(50),
            'name_db' => $this->string(50),
            'label' => $this->string(100),
            'view_col' => $this->string(50),
            'model' => $this->string(50),
            'controller' => $this->string(100),
			'fa_icon' => $this->string(30)->defaultValue("fa-cube"),
			'is_gen' => $this->boolean(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%modules}}');
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
