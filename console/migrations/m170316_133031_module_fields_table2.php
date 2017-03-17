<?php

use yii\db\Migration;

class m170316_133031_module_fields_table2 extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%module_fields}}', [
            'id' => $this->primaryKey()->unsigned(),
            'colname' => $this->string(30),
            'label' => $this->string(100),
            'module' => $this->integer(10) . ' UNSIGNED NOT NULL',
            'field_type' => $this->integer(10) . ' UNSIGNED NOT NULL',
            'unique' => $this->boolean()->defaultValue(false),
            'defaultvalue' => $this->string(),
            'minlength' => $this->integer()->defaultValue(0),
            'maxlength' => $this->integer()->defaultValue(0),
            'required' => $this->boolean()->defaultValue(false),
            'popup_vals' => $this->string(),
            'sort' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'listing_col' => $this->boolean()->defaultValue(true)

        ]);
        $this->addForeignKey(
            'FK_module', '{{%module_fields}}', 'module', '{{%modules}}', 'id', 'CASCADE', 'CASCADE'
        );
        $this->addForeignKey(
            'FK_field_type', '{{%module_fields}}', 'field_type', '{{%module_field_types}}', 'id', 'CASCADE', 'CASCADE'
        );

    }

    public function down()
    {
        $this->dropTable('{{%module_fields}}');
    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addForeignKey(
            'FK_module', '{{%modules}}', 'id', '{{%module_fields}}', 'module', 'SET NULL', 'CASCADE'
        );
        $this->addForeignKey(
            'FK_field_type', '{{%module_field_types}}', 'id', '{{%module_fields}}', 'field_type', 'SET NULL', 'CASCADE'
        );
    }
    /*
    public function safeDown()
    {
    }
    */
}
