<?php

use yii\db\Migration;
use backend\models\Module;

class Create_test11s_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Test11", 'test11s', 'name', 'fa-book', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "Textarea",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('test11s');
        if($tableSchema !== null) {
            $this->dropTable('test11s');
        }
    }
}