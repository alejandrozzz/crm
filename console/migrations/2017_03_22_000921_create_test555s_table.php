<?php

use yii\db\Migration;
use backend\models\Module;

class Create_test555s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test555", 'test555s', 'test', 'ddd555', [
            [
                "colname" => "test",
                "label" => "Test",
                "field_type" => "String",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
                "listing_col" => 1,
                "popup_vals" =>  @companies,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('test555s');
        if($tableSchema !== null) {
            $this->dropTable('test555s');
        }
    }
}