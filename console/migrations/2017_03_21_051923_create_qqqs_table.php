<?php

use yii\db\Migration;

class Create_qqqs_table extends Migration
{
    /**
     * Migration generate Module Table Schema by LaraAdmin
     *
     * @return void
     */
    public function up()
    {
        Module::generate("Qqq", 'qqqs', 'q', 'qqq', [
            [
                "colname" => "q",
                "label" => "q",
                "field_type" => "Address",
                "unique" => 0,
                "defaultvalue" => "",
                "minlength" => 0,
                "maxlength" => 256,
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
		$tableSchema = Yii::$app->db->schema->getTableSchema('qqqs');
        if($tableSchema !== null) {
            $this->dropTable('qqqs');
        }
    }
}