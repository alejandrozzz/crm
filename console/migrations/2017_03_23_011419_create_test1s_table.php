<?php

use yii\db\Migration;
use backend\models\Module;

class Create_test1s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test1", 'test1s', 'name', 'ddd', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "Checkbox",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 0,
                "created_at" => 0,
                "updated_at" => 0,
                "deleted_at" => 0,
                "required" => 0,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('test1s');
        if($tableSchema !== null) {
            $this->dropTable('test1s');
        }
    }
}