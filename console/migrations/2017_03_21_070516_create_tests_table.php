<?php

use yii\db\Migration;
use backend\models\Module;

class Create_tests_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test", 'tests', 'name', 'test', [
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('tests');
        if($tableSchema !== null) {
            $this->dropTable('tests');
        }
    }
}