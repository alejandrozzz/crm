<?php

use yii\db\Migration;

class Create_newmodules_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Newmodule", 'newmodules', 'name', 'fa-click', [
            [
                "colname" => "name",
                "label" => "Name",
                "field_type" => "TextField",
                "unique" => 1,
                "defaultvalue" => "name",
                "minlength" => 0,
                "maxlength" => 0,
                "required" => 0,
                "listing_col" => 0
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('newmodules');
        if($tableSchema !== null) {
            $this->dropTable('newmodules');
        }
    }
}