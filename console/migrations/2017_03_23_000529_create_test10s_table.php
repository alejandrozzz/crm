<?php

use yii\db\Migration;
use backend\models\Module;

class Create_test10s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test10", 'test10s', 'name', 'fa-book', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "Name",
                "unique" => 1,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('test10s');
        if($tableSchema !== null) {
            $this->dropTable('test10s');
        }
    }
}