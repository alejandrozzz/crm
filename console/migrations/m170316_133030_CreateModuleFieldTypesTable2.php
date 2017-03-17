<?php

use yii\db\Migration;
use backend\models\ModuleFieldTypes;

class m170316_133030_CreateModuleFieldTypesTable2 extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%module_field_types}}', [
            'id' =>$this->primaryKey()->unsigned(),
            'name' => $this->string(30),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);
        Yii::$app->cache->flush();
		$model = new ModuleFieldTypes();
        $model->name = "Address";
        $model->save();
		var_dump($model->errors);
        $model = new ModuleFieldTypes();
        $model->name = "Checkbox";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Currency";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Date";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Datetime";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Decimal";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Dropdown";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Email";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "File";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Float";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "HTML";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Image";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Integer";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Mobile";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Multiselect";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Name";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Password";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "String";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Taginput";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Textarea";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "TextField";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "URL";
        $model->save();
        $model = new ModuleFieldTypes();
        $model->name = "Files";
        $model->save();
    }

    public function down()
    {
        $this->dropTable('{{%module_field_types}}');
    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        
    }
    /*
    public function safeDown()
    {
    }
    */
}
