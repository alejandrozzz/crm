<?php

use yii\db\Migration;
use backend\models\Module;

class Create_test2s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test2", 'test2s', 'name', 'test2', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "String",
                "unique" => 1,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 1,
                "listing_col" => 1
            ]
        ]);
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		$tableSchema = Yii::$app->db->schema->getTableSchema('test2s');
        if($tableSchema !== null) {
            $this->dropTable('test2s');
        }
    }
}