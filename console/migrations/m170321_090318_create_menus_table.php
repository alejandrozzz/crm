<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menus`.
 */
class m170321_090318_create_menus_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%menus}}', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(50),
            'url' => $this->string(256),
            'icon' => $this->string(50)->defaultValue("fa-cube"),
            'type' => $this->string(20)->defaultValue("module"),
            'parent' => $this->integer()->unsigned()->defaultValue(0),
            'hierarchy' => $this->integer()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()


        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $tableSchema = Yii::$app->db->schema->getTableSchema('menus');
        if($tableSchema !== null) {
            $this->dropTable('menus');
        }
    }
}
